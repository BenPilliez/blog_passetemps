<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
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
            ->showEntityActionsAsDropdown()
    ;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [];

        if (Crud::PAGE_INDEX === $pageName) {
            array_push(
                $fields,
                IdField::new('id'),
                BooleanField::new('published', 'Publier'),
                TextField::new('title', 'Titre'),
                TextEditorField::new('content', 'Contenu'),
                AssociationField::new('categories', 'Catégories'),
                ArrayField::new('tags'),
                ImageField::new('thumbnail', 'Miniature')
                    ->setBasePath('/media/posts/'),
            );
        } elseif (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            array_push(
                $fields,
                TextField::new('title', 'Titre'),
                TextEditorField::new('content', 'Contenu'),
                AssociationField::new('categories', 'Catégories'),
                AssociationField::new('tags'),
                ImageField::new('thumbnailFile', 'Miniature'),
                CollectionField::new('images', 'Images')
                    ->setEntryType(ImageType::class)
                    ->setFormTypeOption('by_reference', false)
                    ->onlyOnForms(),
                BooleanField::new('published', 'Publier'),
            );
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            array_push(
                $fields,
                IdField::new('id'),
                BooleanField::new('published', 'Publier'),
                TextField::new('title', 'Titre'),
                TextEditorField::new('content', 'Contenu'),
                AssociationField::new('categories', 'Catégories'),
                ArrayField::new('tags'),
                ImageField::new('thumbnail', 'Miniature')
                    ->setBasePath('/media/posts/'),
                CollectionField::new('images', 'Images')
                    ->setTemplatePath('admin/image_show.html.twig')
                    ->addJsFiles('build/admin.js')
            );
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            // the argument of these methods is passed to the asset() Twig function
            // CSS assets are added just before the closing </head> element
            // and JS assets are added just before the closing </body> element

            ->addJsFile('build/app.js')
        ;
    }
}
