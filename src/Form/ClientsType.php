<?php

namespace App\Form;

use App\Entity\Clients;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Countries;


class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
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
            ->add('adresse')
            ->add('cp')
            ->add('ville', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'La ville est obligatoire.']),
                    new Length([
                        'max' => 100,
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
            ])
            ->add('numero_siret')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Clients::class,
        ]);
    }
}
