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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if ($options['onSettingsPage'] === false) {
            $builder
                ->add('email', EmailType::class, [
                    'required' => true,
                ]);

            if (!$options['hideEntrepriseField']) {
                $builder->add('roles', ChoiceType::class, [
                    'choices' => [
                        'Utilisateur' => 'ROLE_ENTREPRISE',
                        'Comptable' => 'ROLE_COMPTABLE',
                        'Administrateur entreprise' => 'ROLE_ADMIN_ENTREPRISE',
                    ],
                    'label' => 'Rôle',
                    'required' => true,
                ]);

                $builder->get('roles')
                    ->addModelTransformer(new CallbackTransformer(
                        function ($rolesArray) {
                            return count($rolesArray) ? $rolesArray[0] : null;
                        },
                        function ($rolesString) {
                            return [$rolesString];
                        }
                    ));
            }

            if (!$options['hideEntrepriseField']) {
                $builder->add('id_entreprise', ChoiceType::class, [
                    'choices' => $options['id_entreprises'],
                    'choice_label' => 'nom',
                    'label' => 'Entreprise',
                    'required' => true,
                    'placeholder' => 'Choisir une entreprise',
                ]);
            }

            if ($options['data']->getId() === null) {
                $builder->add('password', PasswordType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 12,
                            'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères',
                        ]),
                        new Regex([
                            'pattern' => '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/',
                            'message' => 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial',
                        ]),
                    ],
                ]);
            }
        } else {
            $builder
                ->add('theme_color', ChoiceType::class, [
                    'choices' => [
                        'Classique' => 'classique',
                        'Dark' => 'dark',
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
            'hideEntrepriseField' => false,
        ]);
    }
}