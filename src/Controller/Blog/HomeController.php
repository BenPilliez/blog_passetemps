<?php

namespace App\Controller\Blog;

use App\Repository\AboutRepository;
use App\Repository\CategoryRepository;
use App\Repository\ImageRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PostRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(Request $request, CategoryRepository $categoryRepository, ImageRepository $imageRepository, AboutRepository $aboutRepository): Response
    {
        $latestPosts = $this->repository->findLastest();
        $categories = $categoryRepository->findAll();
        $images = $imageRepository->ramdonImage();
        $about = $aboutRepository->about();

        return $this->render('blog/index.html.twig', [
            'latestPosts' => $latestPosts,
            'categories' => $categories,
            'images' => $images,
            'about' => $about,
        ]);
    }
}
