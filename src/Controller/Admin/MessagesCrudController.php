<?php

namespace App\Controller\Admin;

use App\Entity\Messages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class MessagesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Messages::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('status', 'Status'),
            TextField::new('name', 'Nom')
            ->onlyOnForms(),
            AssociationField::new('recipient', 'Destinataire')
            ->onlyOnForms(),
            TextField::new('email', 'Expéditeur'),
            TextField::new('phone', 'Téléphone'),
            TextareaField::new('message', 'Message'),
            DateTimeField::new('createdAt', 'Envoyé le')
            ->onlyOnIndex(),
        ];
    }

}
