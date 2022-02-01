<?php

namespace App\Controller\Admin;

use App\Entity\Messages;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            TextField::new('name', 'Nom'),
            AssociationField::new('recipient', 'Destinataire'),
            TextField::new('email', 'Email'),
            TextField::new('phone', 'Téléphone'),
            TextareaField::new('message', 'Message'),
            DateTimeField::new('createdAt', 'Envoyé le')
        ];
    }

}
