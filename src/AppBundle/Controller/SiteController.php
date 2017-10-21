<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->findAllVisible();

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
