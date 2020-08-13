<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tags::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('Tag')
            ->setEntityLabelInPlural('Tags')

        // the Symfony Security permission needed to manage the entity
        // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission('ROLE_ADMIN')

             // the names of the Doctrine entity properties where the search is made on
        // (by default it looks for in all properties)
            ->setSearchFields(['name'])
        // defines the initial sorting applied to the list of entities
        // (user can later change this sorting by clicking on the table columns)
            ->setDefaultSort(['id' => 'DESC', 'name' => 'ASC'])

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
            TextField::new('name', 'Nom'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setCssClass('btn btn-primary');
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setCssClass('btn btn-danger');
            })
        ;
    }
}
