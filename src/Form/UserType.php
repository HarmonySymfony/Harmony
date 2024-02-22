<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            ->add('password')
            ->add('email')
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ADMIN',
                    'Doctor' => 'DOCTOR',
                    'Laboratoire' => 'LABORATOIRE',
                    'Pharmacien' => 'PHAMACIEN',
                    'Patient' => 'PATIENT',
                    // Add more roles as needed
                ],
            ])
            ->add('nom')
            ->add('prenom')
            ->add('age')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
