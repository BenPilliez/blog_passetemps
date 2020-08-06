<?php

namespace App\Controller\Blog;

use App\Repository\CategoryRepository;
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
    public function index(Request $request, CategoryRepository $categoryRepository): Response
    {
        $latestPosts = $this->repository->findLastest($request->query->getInt('page', 1));
        $categories = $categoryRepository->findAll();

        return $this->render('post/index.html.twig', [
            'latestPosts' => $latestPosts,
            'categories' => $categories,
        ]);
    }
}
