<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Entity\Crew;
use AppBundle\Entity\Gallery;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SiteController extends Controller
{
    /**
     * @Route("/", name="homepage")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('site/index.html.twig', [
            'clients' => $this->getDoctrine()
                ->getRepository(Client::class)
                ->findAllVisible()
        ]);
    }

    /**
     * @Route("/about/", name="about")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction()
    {
        return $this->render('site/about.html.twig', [
            'crew' => $this->getDoctrine()
                ->getRepository(Crew::class)
                ->findAllVisible(),

            'gallery' => $this->getDoctrine()
                ->getRepository(Gallery::class)
                ->findAllVisible()
        ]);
    }
}
