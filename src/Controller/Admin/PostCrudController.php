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
use Vich\UploaderBundle\Form\Type\VichImageType;

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
        $imageFile = ImageField::new('thumbnailFile', 'Miniature')->setFormType(VichImageType::class);
        $image = ImageField::new('thumbnail', 'Miniature')->setBasePath('/media/posts');

        $id = IdField::new('id');
        $published = BooleanField::new('published', 'Publier');
        $title = TextField::new('title', 'Titre');
        $content = TextEditorField::new('content', 'Contenu');
        $categories = AssociationField::new('categories', 'Catégories');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $published, $title, $content, $categories, ArrayField::new('tags'), $image];
        }
        if (Crud::PAGE_EDIT === $pageName || Crud::PAGE_NEW === $pageName) {
            return [$title, $content, $categories, AssociationField::new('tags'),
                $imageFile, CollectionField::new('images', 'Images')
                    ->setEntryType(ImageType::class)
                    ->setFormTypeOption('by_reference', false), $published, ];
        }
        if (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $published, $title, $content, $categories, ArrayField::new('tags'), $image,
                CollectionField::new('images', 'Images')
                    ->setTemplatePath('admin/image_show.html.twig'),
            ];
        }
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets->addCssFile('build/admin.css');
    }
}
