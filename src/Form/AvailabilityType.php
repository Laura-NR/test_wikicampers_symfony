<?php

namespace App\Form;

use App\Entity\Availability;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvailabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('depart_date', null, [
                'widget' => 'single_text',
            ])
            ->add('return_date', null, [
                'widget' => 'single_text',
            ])
            ->add('price_per_day')
            ->add('status')
            ->add('vehicle', EntityType::class, [
                'class' => Vehicle::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Availability::class,
        ]);
    }
}