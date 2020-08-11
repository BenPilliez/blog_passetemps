<?php

namespace App\Controller\Blog;

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
     * @Route("/rating/{id}", name="rating.edit")
     */
    public function index(Request $request, PostRepository $postRepository, RatingRepository $ratingRepository, EntityManagerInterface $em)
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

        return new JsonResponse(['success' => $rate->getRates()]);
    }
}
