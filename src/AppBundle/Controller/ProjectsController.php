<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects/", name="projects")
     */
    public function indexAction(Request $request)
    {
        $types = $this->getDoctrine()
            ->getRepository('AppBundle:ProjectType')
            ->findAll();

        $projects = $this->getDoctrine()
            ->getRepository('AppBundle:Project')
            ->findBy(
                ['display' => true],
                ['position' => 'ASC']
            );

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
            'types' => $types,
        ]);
    }

    /**
     * @Route("/projects/{slug}/", name="projects_show")
     */
    public function showAction(Request $request, $slug)
    {
        $item = $this->getDoctrine()
            ->getRepository('AppBundle:Project')
            ->findOneBy(['url' => $slug]);

        if (is_null($item) || !$item->getDisplay()) {
            throw new NotFoundHttpException();
        }

        return $this->render('projects/show.html.twig', [
            'item' => $item
        ]);
    }
}
