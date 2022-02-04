<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

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
