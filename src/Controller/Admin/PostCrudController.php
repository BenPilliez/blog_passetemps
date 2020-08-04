<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles')

        // the Symfony Security permission needed to manage the entity
        // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission('ROLE_ADMIN')

              // the names of the Doctrine entity properties where the search is made on
        // (by default it looks for in all properties)
            ->setSearchFields(['title', 'createdAt'])
        // defines the initial sorting applied to the list of entities
        // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['id' => 'DESC', 'title' => 'ASC', 'createdAt' => 'DESC'])

        // the max number of entities to display per page
            ->setPaginatorPageSize(30)
        // these are advanced options related to Doctrine Pagination
        // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(true)
    ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title'),
            TextEditorField::new('content'),
            AssociationField::new('tags'),
            AssociationField::new('categories'),
        ];
    }
}
