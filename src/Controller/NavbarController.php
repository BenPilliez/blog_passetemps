<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NavbarController extends AbstractController
{
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function navbar()
    {
        return $this->render('_navbar.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }
}
