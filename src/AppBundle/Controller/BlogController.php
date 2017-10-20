<?php

namespace AppBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogController extends Controller
{
    const PAGE_SIZE = 6;

    /**
     * @Route("/blog/", name="blog")
     */
    public function indexAction(Request $request)
    {
        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findBy(
                ['display' => true],
                ['id' => 'DESC'],
                self::PAGE_SIZE
            );

        /** @var QueryBuilder $qb */
        $qb = $this->getDoctrine()->getRepository('AppBundle:Blog')->createQueryBuilder('b');
        $qb->select('COUNT(b.id)')->where('b.display = true');
        $count = (int) $qb->getQuery()->getSingleScalarResult();

        return $this->render('blog/index.html.twig', [
            'items' => $blog,
            'more' => $count > self::PAGE_SIZE
        ]);
    }

    /**
     * @Route("/blog/page/", name="blog_page")
     */
    public function pageAction(Request $request)
    {
        $page = max((int)$request->get('pageNum', 1), 1);

        $blog = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findBy(
                ['display' => true],
                ['id' => 'DESC'],
                self::PAGE_SIZE,
                self::PAGE_SIZE * ($page - 1)
            );

        if (count($blog)) {
            return new JsonResponse([
                'html' => $this->render('blog/_items.html.twig', [
                    'items' => $blog,
                ]),
                'page' => $page + 1,
                'result' => 'ok'
            ]);
        } else {
            return new JsonResponse([
                'html' => '',
                'page' => 0,
                'result' => 'ok'
            ]);
        }

    }

    /**
     * @Route("/blog/{slug}/", name="blog_show")
     */
    public function showAction(Request $request, $slug)
    {
        $item = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->findOneBy(['url' => $slug]);

        if (is_null($item) || !$item->getDisplay()) {
            throw new NotFoundHttpException();
        }

        return $this->render('blog/show.html.twig', [
            'item' => $item,
            'prev' => $this->getPrevBlog($item->getId()),
            'next' => $this->getNextBlog($item->getId())
        ]);
    }

    protected function getNextBlog($id) {
        $cNext = new \Doctrine\Common\Collections\Criteria();
        $cNext->where($cNext->expr()->eq('display', true))
            ->andWhere($cNext->expr()->gt('id', $id))
            ->setMaxResults(1)
            ->orderBy(["id" => $cNext::ASC]);

        $next = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->matching($cNext);

        return count($next) ? $next[0] : false;
    }

    protected function getPrevBlog($id) {
        $cPrev = new \Doctrine\Common\Collections\Criteria();
        $cPrev->where($cPrev->expr()->eq('display', true))
            ->andWhere($cPrev->expr()->lt('id', $id))
            ->setMaxResults(1)
            ->orderBy(["id" => $cPrev::DESC]);

        $prev = $this->getDoctrine()
            ->getRepository('AppBundle:Blog')
            ->matching($cPrev);

        return count($prev) ? $prev[0] : false;
    }
}
