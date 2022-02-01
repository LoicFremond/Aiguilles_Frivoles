<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            TextField::new('hour', 'Horaires'),
            DateField::new('date', 'Date'),
            TextField::new('address', 'Adresse'),
            TextField::new('postalCode', 'Code Postal'),
            TextField::new('city', 'Ville'),
            TextField::new('description', 'Description'),
            BooleanField::new('status', 'Status'),
        ];
    }
}
