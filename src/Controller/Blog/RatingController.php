<?php

namespace App\Controller\Blog;

use App\Entity\Post;
use App\Entity\Rating;
use App\Repository\PostRepository;
use App\Repository\RatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("rating/{id}", name="rating.index", methods={"GET"})
     */
    public function index(Post $post)
    {
        $rate = $post->getRates() ? $post->getRates()->getCalculateRate() : 0;
        $nbRate = $post->getRates() ? $post->getRates()->getNbRates() : 0;

        $template = $this->render('_rating.html.twig', [
            'rate' => $rate,
            'nbRates' => $nbRate,
            'id' => $post->getId(),
        ])->getContent();

        $response = new JsonResponse();
        $response->setStatusCode(200);

        return $response->setData(['template' => $template]);

        return $this->render('_rating.html.twig', [
            'id' => $post->getId(),
            'rate' => $rate,
            'nbRates' => $nbRate,
        ]);
    }

    /**
     * @Route("/rating/{id}", name="rating.edit", methods={"POST"})
     */
    public function edit(Request $request, PostRepository $postRepository, RatingRepository $ratingRepository, EntityManagerInterface $em)
    {
        $id = $request->get('id');
        $post = $postRepository->find($id);

        if ($post->getRates()) {
            $rate = $ratingRepository->find($post->getRates());
            $rating = $rate->getRates() + intval($request->get('value'));
            $rate->setRates($rating);
            $rate->setNbRates($rate->getNbRates() + 1);

            $em->flush();
        } else {
            $rate = new Rating();
            $rating = intval($request->get('value'));
            $rate->setRates($rating);
            $rate->setNbRates(1);
            $em->persist($rate);
            $em->flush();
            $post->setRates($rate);
            $em->persist($post);
            $em->flush();
        }

        $template = $this->render('_rating.html.twig', [
            'rate' => $rate->getCalculateRate(),
            'nbRates' => $rate->getNbRates(),
            'id' => $post->getId(),
        ])->getContent();
        $response = new JsonResponse();
        $response->setStatusCode(200);

        return $response->setData(['template' => $template]);
    }
}
