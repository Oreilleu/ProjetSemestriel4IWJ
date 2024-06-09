<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\Lots;
use PHPUnit\Framework\Constraint\Callback;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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
            ->add('taxe', null, [
                'constraints' => [
                    new LessThanOrEqual([
                        'value' => 20,
                        'message' => 'La valeur maximale doit être de 20% ou moins.',
                    ]),
                ],
            ])
            ->add('client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'nom', 
            ])
            ->add('id_lots', EntityType::class, [
                'class' => Lots::class,
                'choice_label' => 'adresse', 
            ]);
            
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

        $builder->add('list_produit', HiddenType::class, [
            'mapped' => false,
            'required' => true,
            'constraints' => [
                new NotBlank([
                    'message' => 'La liste des produits ne peut pas être vide.',
                ]),
            ],
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