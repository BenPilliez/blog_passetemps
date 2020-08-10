<?php

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/", name="post.index", methods={"GET"})
     */
    public function index(PostRepository $postRepository, Request $request): Response
    {
        return $this->render('blog/post/index.html.twig', [
            'posts' => $postRepository->findByCategory($request->query->get('id'), $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="post.show", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET"})
     */
    public function show(Post $post): Response
    {
        return $this->render('blog/post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
