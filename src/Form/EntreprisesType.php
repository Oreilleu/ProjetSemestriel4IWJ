<?php

namespace App\Form;

use App\Entity\Entreprises;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;

class EntreprisesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if(!$options['onSettingsPage']) {
            $builder
                ->add('nom')
                ->add('adresse')
                ->add('tel', TextType::class, [
                    'constraints' => [
                        new NotBlank(['message' => 'Le numéro de téléphone est obligatoire.']),
                        new Regex([
                            'pattern' => '/^\+?\d{10,15}$/',
                            'message' => 'Le numéro de téléphone doit contenir entre 10 et 15 chiffres.',
                        ])
                    ],
                ])
                ->add('email')
                ->add('numero_siret')
                ->add('cp')
                ->add('ville', TextType::class, [
                    'constraints' => [
                        new NotBlank(['message' => 'La ville est obligatoire.']),
                        new Length([
                            'min' => 2,
                            'max' => 100,
                            'minMessage' => 'Le nom de la ville doit contenir au moins {{ limit }} caractères.',
                            'maxMessage' => 'Le nom de la ville ne doit pas dépasser {{ limit }} caractères.',
                        ]),
                        new Regex([
                            'pattern' => '/^[a-zA-ZàâäéèêëïîôöùûüçÀÂÄÉÈÊËÏÎÔÖÙÛÜÇ\s\'\-]+$/u',
                            'message' => 'Le nom de la ville ne doit contenir que des lettres, des espaces, des tirets ou des apostrophes.',
                        ]),
                    ],
                ])
                ->add('pays', ChoiceType::class, [
                    'choices' => array_flip(Countries::getNames()),
                    'placeholder' => 'Choisir un pays',
                ]);
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
