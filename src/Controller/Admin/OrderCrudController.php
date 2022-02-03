<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use App\Entity\Product;
use App\Form\OrderType;
use App\Form\ProductType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('status', 'Status'),
            DateTimeField::new('createdAt', 'Commande passée le'),
            AssociationField::new('client', 'Client'),
            AssociationField::new('product', 'Article')
            ->onlyOnForms(),
            CollectionField::new('product', 'Article')
            ->onlyOnIndex(),
            MoneyField::new('price', 'Prix'),
        ];
    }
}
