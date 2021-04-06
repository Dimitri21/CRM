<?php

namespace App\Form;

use App\Entity\CategoryContact;
use App\Entity\Contact;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
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
            ->add('phone',null, [
                'label' => 'Téléphone'
            ])
            ->add('email',EmailType::class, [
                'label' => 'Email',
                'required' => false
            ])
            ->add('company',null, [
                'label' => 'Entreprise'
            ])
            ->add('address',null, [
                'label' => 'Adresse'
            ])
            ->add('categoryContact',EntityType::class, [
                'class' => CategoryContact::class,
                'choice_label' => 'title',
                'label' => 'Catégorie',
            ])
            ->add('description',null, [
                'label' => 'Informations complémentaires'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
