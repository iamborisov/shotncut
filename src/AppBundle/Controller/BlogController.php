<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Repository\BlogRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    /**
     * @Route("/blog/", name="blog")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        /** @var BlogRepository $blogRepository */
        $blogRepository = $this->getDoctrine()->getRepository(Blog::class);

        return $this->render('blog/index.html.twig', [
            'items' => $blogRepository->findAllByPage(1),
            'more' => $blogRepository->countPages() > 1
        ]);
    }

    /**
     * @Route("/blog/page/", name="blog_page")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function pageAction(Request $request)
    {
        /** @var int $pageNum */
        $pageNum = max((int)$request->get('pageNum', 1), 1);

        /** @var Blog[] $items */
        $items = $this->getDoctrine()
            ->getRepository(Blog::class)
            ->findAllByPage($pageNum);

        /** @var array $response */
        $response = [
            'html' => '',
            'page' => 0,
            'result' => 'ok'
        ];

        if (count($items)) {
            $response['html'] = $this->render('blog/_items.html.twig', [
                'items' => $items,
            ]);

            $response['page'] = $pageNum + 1;
        }

        return new JsonResponse($response);
    }

    /**
     * @Route("/blog/{slug}/", name="blog_show")
     *
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($slug)
    {
        /** @var BlogRepository $blogRepository */
        $blogRepository = $this->getDoctrine()->getRepository(Blog::class);

        /** @var Blog $item */
        $item = $blogRepository->findByUrl($slug);

        if (!$item) {
            throw new NotFoundHttpException();
        }

        return $this->render('blog/show.html.twig', [
            'item' => $item,
            'prev' => $blogRepository->findPreviousById($item->getId()),
            'next' => $blogRepository->findNextById($item->getId())
        ]);
    }
}
