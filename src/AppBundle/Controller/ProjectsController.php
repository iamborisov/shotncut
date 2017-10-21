<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\ProjectType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProjectsController extends Controller
{
    /**
     * @Route("/projects/", name="projects")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $this->getDoctrine()
                ->getRepository(Project::class)
                ->findAllVisible(),

            'types' => $this->getDoctrine()
                ->getRepository(ProjectType::class)
                ->findAll(),
        ]);
    }

    /**
     * @Route("/projects/{slug}/", name="projects_show")
     *
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        $item = $this->getDoctrine()
            ->getRepository(Project::class)
            ->findByUrl($slug);

        if (!$item) {
            throw new NotFoundHttpException();
        }

        return $this->render('projects/show.html.twig', [
            'item' => $item
        ]);
    }
}
