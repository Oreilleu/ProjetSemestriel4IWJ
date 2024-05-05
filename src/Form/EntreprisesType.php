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
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('tel')
            ->add('email')
            ->add('numero_siret')
            ->add('rib')
            ->add('created_at')
            ->add('id_devis')
            ->add('id_rapports_financiers')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Entreprises::class,
        ]);
    }
}
