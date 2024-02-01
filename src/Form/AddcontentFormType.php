<?php

namespace App\Form;

use App\Entity\Services;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Form\Extension\Core\Type\BlobType;

class AddcontentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('legende', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Votre légende'
                ],
                'label' => 'Légende'
            ])
            ->add('titre', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Votre titre'
                ],
                'label' => 'Titre'
            ])
            ->add('image', BlobType::class, [
                'attr' =>[
                    'placeholder' => 'Votre image'
                ],
                'label' => 'Image'
            ])
            // liste des différentes catégories de services
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie',
                'placeholder' => 'Choisir une catégorie',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Services::class,
        ]);
    }
}
