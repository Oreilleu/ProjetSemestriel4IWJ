<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $roleHierarchy = [
            'ROLE_ADMIN' => ['ROLE_USER', 'ROLE_ENTREPRISE', 'ROLE_COMPTABLE'],
            'ROLE_ENTREPRISE' => ['ROLE_USER'],
            'ROLE_COMPTABLE' => ['ROLE_USER'],
            'ROLE_USER' => []
        ];

        $choices = array_combine(array_keys($roleHierarchy), array_keys($roleHierarchy));

        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => $choices,
                'multiple' => true,
                'expanded' => true,
            ]);
            if ($options['data']->getId() === null) {
                $builder->add('password', PasswordType::class, [
                    'required' => true,
                ]);
            }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}