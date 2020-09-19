<?php

namespace App\Controller\Blog;

use App\Repository\AboutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="about")
     */
    public function index(AboutRepository $aboutRepository)
    {
        $about = $aboutRepository->about();

        return $this->render('about/index.html.twig', [
            'current_menu' => 'about',
            'about' => $about,
        ]);
    }
}
