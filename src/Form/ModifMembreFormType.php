<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;    
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class ModifMembreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom*',
                'required' => true,

            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom*',
                'required' => true,

            ])
            ->add('description', TextAreaType::class, [
                'required' => false,
                'label' => 'Description',

            ])
            ->add('role', TextAreaType::class, [
                'required' => false,
                'label' => 'Role',
            ])
            // imageFile ( vich_uploader)
            ->add('imageFile', VichImageType::class,
            [
                'required' => false,
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
