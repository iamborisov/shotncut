<?php

namespace AppBundle\Controller;

use AppBundle\Service\ContentService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ContactController extends Controller
{
    /**
     * @Route("/contact/", name="contact")
     */
    public function indexAction(Request $request)
    {
        return $this->render('site/contact.html.twig', [

        ]);
    }

    /**
     * @Route("/contact/send", name="contact_send")
     */
    public function sendAction(
        Request $request,
        \Swift_Mailer $mailer,
        ContentService $settings
    ) {
        $data = $request->get('contact');

        if (
            !isset($data['name']) ||
            !isset($data['email']) ||
            !isset($data['text'])
        ) {
            throw new BadRequestHttpException();
        }

        $result = $this->sendEmail(
            $mailer,
            $settings,
            $data['email'],
            'contact',
            $data
        );

        return new JsonResponse([
            'result' => $result ? 'ok' : 'error'
        ]);
    }

    /**
     * @Route("/contact/calc", name="contact_calc")
     */
    public function calcAction(
        Request $request,
        \Swift_Mailer $mailer,
        ContentService $settings
    ) {
        $data = $request->get('calc');

        if (
            !isset($data['date']) ||
            !isset($data['email']) ||
            !isset($data['common_price'])
        ) {
            throw new BadRequestHttpException();
        }

        $default = [
            'email' => '',
            'common_price' => '',
            'date' => '',
            'type' => '',
            'type_other' => '',
            'audience' => '',
            'aim' => '',
            'time' => '',
            'deadline' => '',
            'example_1' => '',
            'example_2' => '',
            'example_3' => '',
            'idea' => [],
            'scenario' => [],
            'heroes' => '',
            'heroes_prof' => '',
            'heroes_not_prof' => '',
            'hero_search' => '',
            'visagiste' => '',
            'stylist' => '',
            'storyboard' => '',
            'days' => '',
            'equipment[]' => [],
            'equipment_other' => '',
            'team' => '',
            'montage' => '',
            'color' => '',
            'sound' => '',
            'music' => '',
            'music_other' => '',
            'speaker' => '',
            'speaker_lg' => [],
            'copywriter' => '',
            'animation' => '',
            'animation_other' => '',
            'branding' => '',
            'branding_other' => '',
            'social' => [],
            'social_other' => '',
        ];

        foreach ($default as $k => $v) {
            if (!isset($data[$k])) {
                $data[$k] = $v;
            }
        }

        $result = $this->sendEmail(
            $mailer,
            $settings,
            $data['email'],
            'brief',
            $data
        );

        return new JsonResponse([
            'result' => $result ? 'ok' : 'error'
        ]);
    }

    protected function sendEmail(
        \Swift_Mailer $mailer,
        ContentService $settings,
        $recipient,
        $template,
        $data = []
    ) {
        $to = [$recipient];
        foreach (explode(',', $settings->get('contacts.email.'.$template)) as $email) {
            $to[] = trim($email);
        }

        $message = (new \Swift_Message('Hello Email'))
            ->setFrom($settings->get('contacts.email.from'))
            ->setTo($to)
            ->setBody(
                $this->renderView(
                    'email/'.$template.'.txt.twig',
                    $data
                ),
                'text/plain'
            );

        return $mailer->send($message);
    }
}
