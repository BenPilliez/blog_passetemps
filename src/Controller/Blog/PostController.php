<?php

namespace App\Controller\Blog;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    public function __construct(PostRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="post.index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        return $this->render('blog/post/index.html.twig', [
            'posts' => $this->repository->findByCategory($request->query->get('id'), $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * @Route("/{slug}-{id}", name="post.show", requirements={"slug": "[a-z0-9\-]*"}, methods={"GET", "POST"})
     */
    public function show(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $this->em->persist($comment);
            $this->em->flush();
            $this->addFlash('success', 'Votre commentaire a été envoyé et soumis à une validation');

            return $this->redirectToRoute('post.show', [
                'id' => $post->getId(),
                'slug' => $post->getSlug(),
            ]);
        }

        return $this->render('blog/post/show.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
