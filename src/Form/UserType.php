<?php

namespace App\Form;

use App\Entity\Entreprises;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if($options['onSettingsPage'] === false) {
            $builder
                ->add('email', EmailType::class, [
                    'required' => true,
                ])
                ->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Utilisateur' => 'ROLE_ENTREPRISE',
                        'Comptable' => 'ROLE_COMPTABLE',
                    ],
                    'label' => 'Rôle',
                    'required' => true,
                ])
                ->add('id_entreprise', ChoiceType::class, [
                    'choices' => $options['id_entreprises'],
                    'choice_label' => 'nom',
                    'label' => 'Entreprise',
                    'required' => true,
                    'placeholder' => 'Choisir une entreprise',
                ]);

                if ($options['data']->getId() === null) {
                    $builder->add('password', PasswordType::class, [
                        'required' => true,
                    ]);
                }

                $builder->get('roles')
                ->addModelTransformer(new CallbackTransformer(
                    function ($rolesArray) {
                        return count($rolesArray) ? $rolesArray[0] : null;
                    },
                    function ($rolesString) {
                        return [$rolesString];
                    }
                ));
        } else {
            $builder
                ->add('theme_color', ChoiceType::class, [
                    'choices' => [
                        'Classique' => 'classique',
                        'Dark' => 'dark',
                        'Light' => 'light',
                        'Marron' => 'marron',
                        'Bleu' => 'blue',
                    ],
                    'label' => 'Thème',
                    'required' => true,
                    'mapped' => true,
                ]);
        }
    } 

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'onSettingsPage' => false,
            'id_entreprises' => null,
        ]);
    }
}