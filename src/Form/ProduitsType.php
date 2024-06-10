<?php

namespace App\Form;

use App\Entity\CategoriesProduits;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prix', NumberType::class, [
                'scale' => 2,
                'invalid_message' => 'Veuillez entrer un prix valide.',
            ])
            ->add('id_categorie_produits', EntityType::class, [
                'class' => CategoriesProduits::class,
                'choice_label' => 'nom',
                'choices' => $options['categories'],
                'placeholder' => 'Choisir une catégorie',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
            'categories' => CategoriesProduits::class,
        ]);
    }
}
