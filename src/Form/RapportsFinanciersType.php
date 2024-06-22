<?php

namespace App\Form;

use App\Entity\RapportsFinanciers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class RapportsFinanciersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start_date', DateType::class, [
                'widget' => 'single_text', 
                'input' => 'datetime_immutable',
                'label' => 'Date de début',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est obligatoire.']),
                    new GreaterThanOrEqual([
                        'value' => '2000-01-01',
                        'message' => 'La date doit être supérieure ou égale à l\'année 2000.',
                    ]),
                ],
            ])
            ->add('end_date', DateType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'label' => 'Date de fin', 
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'La date de fin est obligatoire.']),
                ],
            ]);
        ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            // Vérifier si les dates sont définies et de type DateTimeImmutable
            $startDate = $data->getStartDate();
            $endDate = $data->getEndDate();

            // Comparer les dates
            if ($endDate < $startDate) {
                // Ajouter une erreur au champ end_date
                $form->get('end_date')->addError(new FormError('La date de fin ne peut pas être inférieure à la date de début.'));
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RapportsFinanciers::class,
        ]);
    }

    
}
