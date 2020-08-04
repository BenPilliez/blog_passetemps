<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tags;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(CategoryCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        // the name visible to end users
            ->setTitle('Blog de mes passe-temps')

        // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('images/favicon.ico')
            ->setTranslationDomain('fr')
        ;
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::section('Blog'),
            MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            MenuItem::linkToCrud('Tags', 'fa fa-tags', Tags::class),
            MenuItem::linkToCrud('Articles', 'fa fa-file-text', Post::class),
        ];
    }
}
