<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SiteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $clients = $this->getDoctrine()
            ->getRepository('AppBundle:Client')
            ->findBy([
                'display' => true
            ], [
                'position' => 'ASC'
            ]);

        return $this->render('site/index.html.twig', [
            'clients' => $clients
        ]);
    }

    /**
     * @Route("/about/", name="about")
     */
    public function aboutAction(Request $request)
    {
        $crew = $this->getDoctrine()
            ->getRepository('AppBundle:Crew')
            ->findBy([
                'display' => true
            ], [
                'position' => 'ASC'
            ]);

        $gallery = $this->getDoctrine()
            ->getRepository('AppBundle:Gallery')
            ->findBy([
                'display' => true
            ], [
                'position' => 'ASC'
            ]);

        return $this->render('site/about.html.twig', [
            'crew' => $crew,
            'gallery' => $gallery
        ]);
    }
}
