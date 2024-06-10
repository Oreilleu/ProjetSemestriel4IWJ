<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Utilisateur' => 'ROLE_ENTREPRISE',
                    'Comptable' => 'ROLE_COMPTABLE',
                ],
                'multiple' => false,
                'expanded' => true,
                'label' => 'Rôle',
                'label_attr' => ['class' => 'block text-sm font-medium text-gray-700 mb-2'],
                'attr' => ['class' => 'flex space-x-4'],
                'choice_attr' => function($choice, $key, $value) {
                    return ['class' => 'form-radio text-blue-600 cursor-pointer'];
                },
            ]);
            if ($options['data']->getId() === null) {
                $builder->add('password', PasswordType::class, [
                    'required' => true,
                ]);
                // Transformer une chaîne en array pour les rôles
        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesArray) {
                // Transforme l'array en string pour le champ formulaire
                return count($rolesArray) ? $rolesArray[0] : null;
            },
            function ($rolesString) {
                // Transforme la string en array pour l'entité
                return [$rolesString];
            }
        ));
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}