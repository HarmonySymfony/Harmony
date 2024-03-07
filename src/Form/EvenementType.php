<?php

namespace App\Form;

use App\Entity\Evenement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'=> [
                    'placeholder' =>'Nom évènement',
                    'class'=> 'form-control'
                ]
            ])
            ->add('dateEvent', DateType::class,[
                'widget' => 'single_text',
                'attr' =>[
                    'class' => 'form-control',
                    'placeholder'=> 'dd/mm/yyyy',
                    'type'=>'date',

                ]
            ])
            ->add('description', TextType::class, [
                'attr'=> [
                    'placeholder' =>'Description',
                    'class'=> 'form-control'
                ]
            ])
            ->add('prix', TextType::class, [
                'attr'=> [
                    'placeholder' =>'Prix évènement',
                    'class'=> 'form-control'
                ]
            ])
            // ->add('adresse', TextType::class, [
            //     'attr'=> [
            //         'placeholder' =>'Adresse évènement',
            //         'class'=> 'form-control'
            //     ]
            // ])
            // ->add('longitude')
            // ->add('latitude')
            ->add('placeDispo', TextType::class, [
                'attr'=> [
                    'placeholder' =>'Capacité',
                    'class'=> 'form-control'
                ]
            ])
            ->add('image', FileType::class, [
                'attr' => ['class' => 'form-control'],
                'required' => false,
                'mapped' => false,
                // array('data_class' => null),
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}