<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
        return Category::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            MoneyField::new('price', 'Prix'),
            TextareaField::new('description', 'Description'),
            BooleanField::new('status', 'Disponible'),
            ImageField::new('picture', 'Photo'),
            TextField::new('material', 'Materiaux'),
            TextField::new('gems', 'Gemmes'),
            TextField::new('gems2', 'Gemmes')
            ->hideOnIndex(),
            TextField::new('size', 'Dimension')
            ->hideOnIndex(),
            TextField::new('closing', 'Fermeture')
            ->hideOnIndex(),
            AssociationField::new('category', 'Collection'),
            SlugField::new('slug', 'Slug - Actualisation Automatique')
            ->hideOnIndex()
            ->setTargetFieldName('name'),
        ];
    }

}