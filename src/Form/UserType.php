<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'Exemple@email.com']
            ])
            ->add('Password', PasswordType::class, [
                'mapped'            => false,
                'label' => 'Mot de passe',
                'invalid_message' => 'Le mot de passe n\'est pas valide',
                'attr' => ['placeholder' => 'Votre mot de passe']
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => ['placeholder' => 'Votre nom']
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['placeholder' => 'Votre prénom']
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'attr' => ['placeholder' => 'Votre numéro de téléphone']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ville d\'habitation']
            ])
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'attr' => ['placeholder' => 'Numéro et nom de la rue']
            ])
            ->add('postalCode', TextType::class, [
                'label' => 'Code Postal',
                'attr' => ['placeholder' => 'Code postal']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}