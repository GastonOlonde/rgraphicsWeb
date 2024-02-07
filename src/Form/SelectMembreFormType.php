<?php

namespace App\Form;

use App\Entity\Membre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class SelectMembreFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // prenom qui sera à sélectionner avec une liste déroulante
        ->add('prenom', EntityType::class, [
            'class' => Membre::class,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('m')
                    ->orderBy('m.prenom', 'ASC');
            },
            'choice_label' => 'prenom',
            'attr' => [
                'class' => 'select-membre-autocomplete', 
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Membre::class,
            'csrf_protection' => false, // Disable CSRF protection for AJAX requests
        ]);
    }
}
