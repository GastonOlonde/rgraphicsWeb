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
        ])
        ->add('prenom', TextType::class, [
            'label' => 'Prenom',
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
        ])
        ->add('telephone', NumberType::class, [
            'label' => 'Telephone',
        ])
        ->add('objet', TextType::class, [
            'label' => 'Objet',
        ])
        ->add('message', TextareaType::class, [
            'label' => 'Message',
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
