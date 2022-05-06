<?php

namespace App\Form;

use App\Entity\Agent;
use App\Entity\Contact;
use App\Entity\Country;
use App\Entity\Hideouts;
use App\Entity\Mission;
use App\Entity\Specialities;
use App\Entity\StatusMission;
use App\Entity\Target;
use App\Entity\TypeMission;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Ex : Mission Impossible !'
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Ex : "Une mission, que pour les meilleurs agents qui ..."'
                ]
            ])
            ->add('code_name', TextType::class, [
                'label' => 'Nom de code',
                'attr' => [
                    'placeholder' => ' Ex : "Spy X"'
                ]
            ])
            ->add('start_date', DateType::class, [
                'label' => 'Date de début de la mission',
            ])
            ->add('end_date', DateType::class, [
                'label' => 'Date de fin de la mission'
            ])
            ->add('country', EntityType::class, [
                'label' => 'Pays de la mission',
                'choice_label' => 'name',
                'class' => Country::class,
            ])
            ->add('agents', EntityType::class, [
                'label' => 'Agent(s) (doit être d\'un pays différent de la mission)',
                'choice_label' => function ($agents) {

                    return $agents->getIdCode() . " (" . $agents->getNationality() . " | Compétences :  ".  implode(", ", $agents->displaySpecialities()) . ")";
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'class' => Agent::class
            ])
            ->add('contacts', EntityType::class, [
                'label' => 'Contact(s) (doit être du même pays que la mission)',
                'choice_label' => function($contacts)
                {
                    return $contacts->getCodeName() . " (" . $contacts->getNationality() . ")";
                },
                'multiple' => true,
                'expanded' => true,
                'required' => true,
                'class' => Contact::class
            ])
            ->add('targets', EntityType::class, [
                'label' => 'Cible(s) (doit être du même pays que la mission)',
                'choice_label' => function ($targets) {
                    return $targets->getCodeName() . " (" . $targets->getNationality() . ")";
                },
                'class' => Target::class,
                'multiple' => true,
                'expanded' => true,
                'required' => true
                //'mapped' => false
            ])
            ->add('hideouts', EntityType::class, [
                'label' => 'Planque (doit être identique au pays de la mission)',
                'choice_label' => function($hideouts) {
                    return $hideouts->getAlias() . ' ' . "(".$hideouts->getCountry().")";
                },
                'mapped' => false,
                'class' => Hideouts::class
            ])
            ->add('specialities', EntityType::class, [
                'label' => 'Spécialité nécessaire à la mission',
                'required' => true,
                'class' => Specialities::class
            ])
            ->add('type_of_mission', EntityType::class, [
                'label' => 'Type de mission',
                'class' => TypeMission::class
            ])
            ->add('status', EntityType::class, [
                'label' => 'Status de la mission',
                'class' => StatusMission::class
            ])
            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mission::class,
        ]);
    }
}
