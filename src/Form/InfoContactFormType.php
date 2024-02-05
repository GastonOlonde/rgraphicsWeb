<?php

namespace App\Form;

use App\Entity\Parametre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class InfoContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        //nom_param qui sera à sélectionner avec une liste déroulante
            ->add('nom_param', EntityType::class, [
                'class' => Parametre::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.nom_param NOT IN (:nom_param)')
                        ->setParameter( 'nom_param', ['LOGO', 'TEXTE_ACCUEIL']);
                },
                'choice_label' => 'nom_param',
                'label' => 'Nom du paramètre'
            ])
            ->add('value_param', TextareaType::class, [
                'attr' =>[
                    'placeholder' => 'Changer ici...'
                ],
                'label' => 'Valeur du paramètre'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Parametre::class,
        ]);
    }
}
