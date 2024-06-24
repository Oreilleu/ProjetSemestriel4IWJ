<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Devis;
use App\Entity\Lots;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Length;
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
                'input' => 'datetime_immutable',
                'label' => 'Date',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => '2000-01-01',
                        'message' => 'La date doit être supérieure ou égale à l\'année 2000.',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new NotBlank(['message' => 'La description est obligatoire.']),
                    new Length([
                        'max' => 40,
                        'maxMessage' => 'La description ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('taxe', null, [
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 0,
                        'message' => 'La valeur minimale doit être de 0% ou plus.',
                    ]),
                    new LessThanOrEqual([
                        'value' => 20,
                        'message' => 'La valeur maximale doit être de 20% ou moins.',
                    ]),
                ],
            ])
            ->add('client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'nom',
                'choices' => $options['clients'],
                'placeholder' => 'Choisir un client',
            ])
            ->add('id_lots', EntityType::class, [
                'class' => Lots::class,
                'choice_label' => 'adresse',
                'choices' => $options['lots'],
                'placeholder' => 'Choisir un lot',
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
            'help' => 'Les produits sélectionnés s\'afficheront dans la liste des produits ci-dessous.',
            'error_bubbling'=> false,
            'constraints' => [
            new NotBlank([
                'message' => 'La liste des produits ne peut pas être vide.',
            ]),
            new Callback([
                'callback' => function ($value, ExecutionContextInterface $context) {
                    if (is_array($value) && empty($value)) {
                        $context->buildViolation('La liste des produits ne peut pas être vide.')
                                ->addViolation();
                    }
                    if ($value == '[]') {
                        $context->buildViolation('La liste des produits ne peut pas être vide.')
                                ->addViolation();
                    }
                },
            ]),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
            'show_statut_field' => false,
            'clients' => Clients::class,
            'lots' => Lots::class
        ]);
    }
}