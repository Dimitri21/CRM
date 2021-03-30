<?php

namespace App\Form;

use App\Entity\Calendar;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',null, [
                'label' => "Titre de l'évènement",
            ])
            ->add('start', DateTimeType::class, [
                'label' => "Début de l'évènement",
                'date_widget' => 'single_text'
            ])
            ->add('end', DateTimeType::class, [
                'label' => "Fin de l'évènement",
                'date_widget' => 'single_text'
            ])
            ->add('description')
            ->add('backgroundColor', ColorType::class,[
                    'label' => "Couleur de l'évènement",
                    'attr' => [
                        'class' => 'bgColorPicker',
                        'value' => '#2a6cb8'
                    ],
                ]
            )
            ->add('members',EntityType::class, [
                'class' => User::class,
                'choice_label' => function($allChoices, $currentChoiceKey) {
                    return $allChoices->getLastName() . " " . $allChoices->getFirstName();
                    },
                'multiple' => true,
                'label' => 'Membres',
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.lastName', 'ASC');
                },
                'by_reference'=> false,
                'attr' => [
                    'class' => 'userPicker'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
