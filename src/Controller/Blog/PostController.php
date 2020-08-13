<?php

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Helper\Processor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    public function __construct(PostRepository $repository, EntityManagerInterface $em, Processor $processor)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->processor = $processor;
    }

    /**
     * @Route("/tag", name="post.tag")
     */
    public function IndexbyTag(Request $request)
    {
        $post = $this->repository->findByTag($request->query->getInt('tag_id'), $request->query->getInt('page', 1));

        if ($request->isXmlHttpRequest()) {
            $template = $this->render('blog/post/_postLoop.html.twig', [
                'posts' => $post,
            ])->getContent();
            $paginationContext = $this->processor->render($post);
            $twig = $this->get('twig');
            $paginationHtml = $twig->render($post->getTemplate(), $paginationContext);
            $response = new JsonResponse();
            $response->setStatusCode(200);

            return $response->setData(['template' => $template, 'pagination' => $paginationHtml]);
        }

        return $this->render('blog/post/index.html.twig', [
            'posts' => $post,
        ]);
    }

    /**
     * @Route("/", name="post.index", methods={"GET"})
     */
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $post = $this->repository->findByCategory($request->query->get('id'), $request->query->getInt('page', 1));

        if (0 === count($post->getItems())) {
            $category = $categoryRepository->find($request->query->get('id'));

            return $this->render('blog/post/index.html.twig', [
                'posts' => $post,
                'category' => $category,
            ]);
        }

        if ($request->isXmlHttpRequest()) {
            $template = $this->render('blog/post/_postLoop.html.twig', [
                'posts' => $post,
            ])->getContent();
            $paginationContext = $this->processor->render($post);
            $twig = $this->get('twig');
            $paginationHtml = $twig->render($post->getTemplate(), $paginationContext);
            $response = new JsonResponse();
            $response->setStatusCode(200);

            return $response->setData(['template' => $template, 'pagination' => $paginationHtml]);
        }

        return $this->render('blog/post/index.html.twig', [
            'posts' => $post,
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="post.show", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET", "POST"})
     */
    public function show(Post $post, Request $request, CommentController $commentController): Response
    {
        return $this->render('blog/post/show.html.twig', [
            'post' => $post,
            'comments' => $commentController->list($post, $request),
        ]);
    }
}
