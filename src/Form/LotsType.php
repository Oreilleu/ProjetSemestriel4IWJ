<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Lots;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Intl\Countries;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LotsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('superficie',NumberType::class,[
                'scale' => 2,
                'invalid_message' => 'Veuillez saisir un nombre valide'
            ])
            ->add('type')
            ->add('adresse', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'L\'adresse est obligatoire.']),
                    new Length([
                        'min' => 7,
                        'max' => 255,
                        'minMessage' => 'L\'adresse doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZàâäéèêëïîôöùûüçÀÂÄÉÈÊËÏÎÔÖÙÛÜÇ0-9\s,.\'\/\-]+$/u',
                        'message' => 'L\'adresse n\'est pas valide.',
                    ]),
                ]
            ])
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
            ])
            ->add('id_client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'email',
                'choices' => $options['clients'],
                'placeholder' => 'Choisir un client',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lots::class,
            'clients' => Clients::class,
        ]);
    }
}
