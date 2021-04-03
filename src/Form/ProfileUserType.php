<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('firstName',null, [
                'label' => 'Prénom'
            ])
            ->add('lastName',null, [
                    'label' => 'Nom'
                ])
            ->add('imageFile', FileType::class, [
                'required' => false,
                'label' => 'Photo de profil',
                'attr' => [
                    'id' => 'uploadBtn'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
