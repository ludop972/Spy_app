<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Country;
use App\Entity\Specialities;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AgentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'attr' => [
                    'placeholder' => 'Merci de renseigner un prénom'
                ]
            ])

            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Merci de renseigner un nom'
                ]
            ])

            ->add('date_of_birth', BirthdayType::class, [
                'label' => 'Date de naissance'
            ])
            ->add('id_code', TextType::class, [
                'label' => 'Nom de code',
                'attr' => [
                    'placeholder' => 'Merci de renseigner un nom de code ex:007'
                ]
            ])
            ->add('nationality', EntityType::class, [
                'label' => 'Nationalité',
                'class' => Country::class,
                'choice_label' => 'name'
            ])
            ->add('specialities', EntityType::class, [
                'label' => 'Spécialités',
                'class' => Specialities::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            //->add('missions')
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-block btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Agent::class,
        ]);
    }
}
