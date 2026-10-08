<?php

namespace App\Controller\Admin;

use App\Entity\Movie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

/**
 * @extends AbstractCrudController<Movie>
 */
class MovieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Movie::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Film')
            ->setEntityLabelInPlural('Films')
            ->setSearchFields(['title'])
            ->setDefaultSort(['title' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();

        // Posters are external URLs: show the image in lists, edit the URL in forms.
        if (in_array($pageName, [Crud::PAGE_INDEX, Crud::PAGE_DETAIL], true)) {
            yield ImageField::new('poster', 'Affiche');
        } else {
            yield UrlField::new('poster', 'Affiche (URL)');
        }

        yield TextField::new('title', 'Titre');
        yield IntegerField::new('releaseDate', 'Année');
        yield TextareaField::new('description')->hideOnIndex();
        yield AssociationField::new('categories', 'Catégories')
            ->setFormTypeOption('by_reference', false);
        yield AssociationField::new('actors', 'Acteurs')
            ->autocomplete()
            ->setFormTypeOption('by_reference', false);
    }
}
