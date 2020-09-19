<?php

namespace App\Controller\Admin;

use App\Entity\About;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Vich\UploaderBundle\Form\Type\VichImageType;

class AboutCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return About::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        // the labels used to refer to this entity in titles, buttons, etc.
            ->setEntityLabelInSingular('A propos')
            ->setEntityLabelInPlural('A propos')

        // the Symfony Security permission needed to manage the entity
        // (none by default, so you can manage all instances of the entity)
            ->setEntityPermission('ROLE_ADMIN')

        // the max number of entities to display per page
            ->setPaginatorPageSize(30)
        // these are advanced options related to Doctrine Pagination
        // (see https://www.doctrine-project.org/projects/doctrine-orm/en/2.7/tutorials/pagination.html)
            ->setPaginatorUseOutputWalkers(true)
            ->setPaginatorFetchJoinCollection(true)
            ->showEntityActionsAsDropdown()
    ;
    }

    public function configureFields(string $pageName): iterable
    {
        $avatarFile = ImageField::new('avatarFile', 'Avatar')->setFormType(VichImageType::class);
        $avatar = ImageField::new('avatar')->setBasePath('/media/avatars');

        $description = TextEditorField::new('content', 'Description');

        if (Crud::PAGE_INDEX === $pageName || Crud::PAGE_DETAIL === $pageName) {
            return[$description, $avatar];
        }
        if (Crud::PAGE_NEW === $pageName || Crud::PAGE_EDIT) {
            return[$description, $avatarFile];
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
    ;
    }
}
