<?php

namespace App\Notification;

use App\Entity\Comment;
use App\Entity\Post;
use Twig\Environment;

class Notification
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var Environment
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }

    /**
     * notify.
     *
     * @param mixed $post
     */
    public function notify(Post $post, Comment $comment)
    {
        $message = (new \Swift_Message('Post :'.$post->getId()))
            ->setFrom('noreply@server.com')
            ->setTo('madeleine.f@live.fr')
            ->setBody($this->renderer->render('emails/notify.html.twig', ['post' => $post, 'comment' => $comment]), 'text/html')
        ;

        $this->mailer->send($message);
    }
}
