<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Country;
use App\Entity\Specialities;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            'method' => 'GET',
            'crsf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Votre Recherche ....',
                    'class' => 'form-control-sm',
                ],
            ])
            ->add('specialities', EntityType::class, [
                'label' => 'Filtrer par spécialité :',
                'required' => false,
                'class' => Specialities::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('nationalities', EntityType::class, [
                'label' => "Filtrer par nationalité :",
                'required' => false,
                'class' => Country::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('submit', Submittype::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-success btn-sm'
                ]
            ]);
    }
}

