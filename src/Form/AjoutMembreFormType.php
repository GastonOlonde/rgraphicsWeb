<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class AjoutMembreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',

            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',

            ])
            ->add('description', TextAreaType::class, [
                'label' => 'Description',

            ])
            ->add('role', TextAreaType::class, [
                'label' => 'Role',
            ])
            ->add('imageFile', VichImageType::class,
            [
                'required' => true,
                'label' => 'Photo',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
        ]);
    }
}
