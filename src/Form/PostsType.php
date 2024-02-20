<?php

namespace App\Form;

use App\Entity\Posts;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Import ChoiceType
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType; // Import TextareaType

class PostsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postedAs', ChoiceType::class, [
                'label' => 'Publier en tant que :',
                'choices' => [
                    'Mon nom d\'utilisateur' => 'user',
                    'Anonyme' => 'anonymous',
                ],
                'expanded' => true, // Display choices as radio buttons
                'required' => true, // Required field
                'choice_attr' => function($choice, $key, $value) {
                    return ['style' => 'margin-left: 50px; margin-right: 10px']; // Add margin to each choice
                },
            ])
            ->add('contenu', TextareaType::class, [
                'label' => 'Question publiée :',
                'attr' => ['rows' => 8, 'cols' => 150,'placeholder' => 'Ecrivez votre question ici...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
