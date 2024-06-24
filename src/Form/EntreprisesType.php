<?php

namespace App\Form;

use App\Entity\Entreprises;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntreprisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(!$options['onSettingsPage']) {
            $builder
                ->add('nom')
                ->add('adresse')
                ->add('tel')
                ->add('email')
                ->add('numero_siret');

        }
        
        if ($options['onSettingsPage']) {
            $builder
                ->add('interval_relance_devis')
                ->add('interval_relance_factures');
        }
    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprises::class,
            'onSettingsPage' => false,
        ]);
    }
}
