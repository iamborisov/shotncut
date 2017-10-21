<?php

namespace AppBundle\Controller;

use AppBundle\Service\MailerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContactController extends Controller
{
    /**
     * @Route("/contact/", name="contact")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('site/contact.html.twig');
    }

    /**
     * @Route("/contact/send", name="contact_send")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendAction(Request $request) {
        /** @var MailerService $mailer */
        $mailer = $this->get('mailer.contact');

        /** @var array $data */
        $data = $request->get('contact');

        if (!$mailer->validate($data)) {
            throw new BadRequestHttpException();
        }

        return new JsonResponse([
            'result' => $mailer->send($data['email'], $data)
                ? 'ok'
                : 'error'
        ]);
    }

    /**
     * @Route("/contact/calc", name="contact_calc")
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
