<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\Lots;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'help' => 'Date sous forme DD/MM/YYYY',
            ])
            ->add('description')
            ->add('taxe')
            ->add('client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'nom', 
            ])
            ->add('id_lots', EntityType::class, [
                'class' => Lots::class,
                'choice_label' => 'adresse', 
            ]);
            
        // Affiche le champ "statut" uniquemen si on a passé l'option appropriée depuis le contrôleur
        if ($options['show_statut_field']) {
            $builder->add('statut', ChoiceType::class, [
                'choices' => [
                    'En cours' => 'En cours',
                    'Refusé' => 'Refusé',
                    'Accepté' => 'Accepté',
                ],
                'data' => 'En cours',
            ]);
        }

        $builder
            ->add('list_produit', EntityType::class, [
                'class' => Produits::class, 
                'choice_label' => function ($produit) {
                    return $produit->getNom() . ' - ' . $produit->getPrix() . ' €';
                },
                'expanded' => false,
                'mapped' => false,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
            'show_statut_field' => false, 
        ]);
    }
}