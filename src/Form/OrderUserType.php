<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Entity\Adresse;
use App\Entity\Carrier;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => "Choisissez votre adresse de livraison",
                'required' => true,
                'class' => Adresse::class,
                'expanded' => true,
                'choices' => $options['addresses'],
                'label_html' => true,

            ])

            ->add('carriers', EntityType::class, [
                'label' => "Choisissez votre transporteur",
                'required' => true,
                'class' => Carrier::class,
                'expanded' => true,
                'label_html' => true,

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Valider',
                'attr' => [
                    'class' => 'btn btn-dark'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'addresses' => null,
        ]);
    }
}
