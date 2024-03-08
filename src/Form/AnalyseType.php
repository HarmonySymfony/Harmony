<?php

namespace App\Form;

use App\Entity\Analyse;
use App\Entity\Laboratoires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AnalyseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder 
       ->add('laboratoire', EntityType::class, [
            'class' => 'App\Entity\Laboratoires',
            'choice_label' => 'nom'
        ])
       
        ->add('type')
        ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Analyse::class,
        ]);
    }
}
