<?php

namespace App\Form;

use App\Entity\Clients;
use App\Entity\Lots;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ->add('adresse')
            ->add('id_client', EntityType::class, [
                'class' => Clients::class,
                'choice_label' => 'email',
                'placeholder' => 'Choisir un client',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lots::class,
        ]);
    }
}
