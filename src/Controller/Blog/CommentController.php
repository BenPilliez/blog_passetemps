<?php

namespace App\Controller\Blog;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Notification\Notification;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\PaginatorBundle\Helper\Processor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * @var CommentRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var Processor
     */
    private $processor;

    public function __construct(CommentRepository $repository, EntityManagerInterface $em, Processor $processor)
    {
        $this->repository = $repository;
        $this->em = $em;
        $this->processor = $processor;
    }

    public function commentForm(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $id = $request->get('id');

        $class = $request->get('class');
        $title = $request->get('title');
        $path = $request->get('path');
        $form_id = $request->get('form_id');

        return $this->render('blog/post/_commentForm.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'class' => $class,
            'title' => $title,
            'path' => $path,
            'form_id' => $form_id,
        ]);
    }

    /**
     * @Route("/{id}", name="comment.new")
     */
    public function new(Request $request, Post $post, Notification $notify): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setPost($post);
            $comment->setPublished(false);
            $notify->notify($post, $comment);
            $this->em->persist($comment);
            $this->em->flush();
            $this->addFlash('success', 'Votre commentaire a été envoyé et soumis à une validation');

            return $this->redirectToRoute('post.show', [
                'id' => $post->getId(),
                'slug' => $post->getSlug(),
            ]);
        }
    }

    /**
     * @Route("/edit/{id}", name="comment.edit")
     */
    public function edit(Comment $comment, Request $request)
    {
        $commentReply = new Comment();
        $form = $this->createForm(CommentType::class, $commentReply);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentReply->setComment($comment);
            $this->em->persist($commentReply);
            $this->em->flush();
            $this->addFlash('success', 'Votre commentaire a été envoyé et soumis à une validation');

            return $this->redirectToRoute('post.show', [
                'id' => $comment->getPost()->getId(),
                'slug' => $comment->getPost()->getSlug(),
            ]);
        }
    }

    /**
     * @Route("/list/{id}", name="comment.list", methods={"GET"})
     */
    public function list(Post $post)
    {
        $request = Request::createFromGlobals();
        $published = $this->repository->findPublished($post->getId());
        $comment = $this->repository->paginateComment($published, $request->query->get('page', 1));

        $comment->setUsedRoute('comment.list');

        //ajax request
        if ($request->isXmlHttpRequest()) {
            $template = $this->render('blog/post/_comments.html.twig', [
                'comments' => $comment,
            ])->getContent();
            $paginationContext = $this->processor->render($comment);
            $twig = $this->get('twig');
            $paginationHtml = $twig->render($comment->getTemplate(), $paginationContext);
            $response = new JsonResponse();
            $response->setStatusCode(200);

            return $response->setData(['template' => $template, 'pagination' => $paginationHtml]);
        }

        return $comment;
    }
}
