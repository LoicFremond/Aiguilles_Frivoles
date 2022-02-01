<?php

namespace App\Form;

use App\Entity\Messages;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',
            TextType::class, [
                'label' => 'Nom'
            ])
            ->add('email',
            TextType::class, [
                'label' => 'Email'
            ])
            ->add('message',
            TextareaType::class, [
                'label' => 'Votre message'
            ])
            ->add('phone',
            TextType::class, [
                'label' => 'Téléphone'
            ])
            ->add('recipient',
            EntityType::class, [
                'class' => User::class,
                'choice_label' => 'lastname',
                'label' => 'Utilisateur'
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Messages::class,
        ]);
    }
}
