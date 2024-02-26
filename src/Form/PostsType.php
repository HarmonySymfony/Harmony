<?php

namespace App\Form;

use App\Entity\Posts;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security; // Import Security class
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PostsType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder

            ->add('postedAs', ChoiceType::class, [
                'label' => 'Publier en tant que',
                'choices' => [
                    $user->getPrenom() => $user->getPrenom(),
                    'Anonyme' => 'Anonyme',
                ],
                'expanded' => true,
                'required' => true,
                'choice_attr' => function($choice, $key, $value) {
                    return ['style' => 'margin-left: 50px; margin-right: 10px'];
                },
                'data' => $user->getPrenom(), // Preselect the user's first name
            ])


            ->add('contenu', TextareaType::class, [
                'label' => 'Question publiée :',
                'attr' => ['rows' => 8, 'cols' => 150, 'placeholder' => 'Ecrivez votre question ici...']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Posts::class,
        ]);
    }
}
