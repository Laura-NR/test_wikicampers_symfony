<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchAvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('depart_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ'
            ])
            ->add('return_date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour'
            ])
            ->add('max_price', NumberType::class, [
                'required' => false,
                'label' => 'Prix maximum de la location'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
