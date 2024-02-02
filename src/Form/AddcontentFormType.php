<?php

namespace App\Form;

use App\Entity\Services;
use App\Entity\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// use Doctrine\DBAL\Types\Types;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

class AddcontentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' =>[
                    'placeholder' => 'Titre'
                ],
                'label' => 'Titre'
            ])
            // tectarea pour la légende
            ->add('legende', TextAreaType::class, [
                'attr' =>[
                    'placeholder' => 'Légende'
                ],
                'label' => 'Légende'
            ])
            // Input pour l'image avec vichuploader File
            ->add('imageFile', VichImageType::class, [
                'attr' =>[
                    'placeholder' => 'Votre image'
                ],
                'label' => 'Image'
            ])
            
            // Valeures de la table catégories qui seront à selectionner depuis une liste déroulante dans le formulaire d'ajout de service
            ->add('categorie', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'nom',
                'label' => 'Catégorie'
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
