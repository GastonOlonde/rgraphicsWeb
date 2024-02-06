<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, NumberType, TextareaType, SubmitType};

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'attr' => [
                'placeholder' => "Votre Nom",
            ],
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prénom',
            'attr' => [
                'placeholder' => "Votre Prénom",
            ],
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr' => [
                'placeholder' => "Votre Email",
            ],
        ])
        ->add('telephone', NumberType::class, [
            'label' => 'Téléphone',
            'attr' => [
                'placeholder' => "Votre Téléphone",
            ],
        ])
        ->add('objet', TextType::class, [
            'label' => 'Objet',
            'attr' => [
                'placeholder' => "Votre Objet",
            ],
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Message',
            'attr' => [
                'placeholder' => "Votre Message",
            ],
        ])
        ->add('envoyer', SubmitType::class, [
            'label' => 'Envoyer',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
