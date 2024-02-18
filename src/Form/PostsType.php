<?php

namespace App\Form;

use App\Entity\Posts;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu')
//            ->add('date_creation')
            // ->add('user')

            ->add('user', EntityType::class, [
                'class' => User::class, // Specify the class of the entity for the dropdown list
                'choice_label' => 'username', // Specify the property of the entity to display as the option label
                'placeholder' => 'Select a user', // Optional: Add a placeholder option
                'required' => true, // Optional: Specify if the field is required
                // You can add more options here as needed
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
