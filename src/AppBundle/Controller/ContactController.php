<?php

namespace AppBundle\Controller;

use AppBundle\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContactController extends Controller
{
    /**
     * @Route("/contacts", name="contact")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('site/contact.html.twig');
    }

    /**
     * @Route("/contacts/captcha", name="captcha")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function captchaAction()
    {
        $options = $this->container->getParameter('gregwar_captcha.config');
        $session = $this->get('session');

        /* @var \Gregwar\CaptchaBundle\Generator\CaptchaGenerator $generator */
        $generator = $this->container->get('gregwar_captcha.generator');

        $persistedOptions = $session->get('captcha', array());
        $options = array_merge($options, $persistedOptions);

        $phrase = $generator->getPhrase($options);
        $generator->setPhrase($phrase);
        $persistedOptions['phrase'] = $phrase;
        $session->set('captcha', $persistedOptions);

        $response = new Response($generator->generate($options));
        $response->headers->set('Content-type', 'image/jpeg');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Cache-Control', 'no-cache');

        return $response;
    }

    /**
     * @Route("/contacts/send", name="contact_send")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendAction(Request $request) {
        /** @var MailerService $mailer */
        $mailer = $this->get('mailer.contact');

        /** @var array $data */
        $data = $request->get('contact');

        $session = $this->get('session');

        $captchaPhrase = $session->get('captcha')['phrase'];
        $captchaInput = $request->get('captcha');

        $session->set('captcha', []);

        if (empty($captchaPhrase) || ($captchaPhrase != $captchaInput) || !$mailer->validate($data)) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse([
            'result' => $mailer->send($data['email'], $data)
                ? 'ok'
                : 'error'
        ]);
    }

    /**
     * @Route("/contacts/calc", name="contact_calc")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function calcAction(Request $request) {
        /** @var MailerService $mailer */
        $mailer = $this->get('mailer.calc');

        /** @var array $data */
        $data = $request->get('calc');

        if (!$mailer->validate($data)) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse([
            'result' => $mailer->send($data['email'], $data)
                ? 'ok'
                : 'error'
        ]);
    }
}
