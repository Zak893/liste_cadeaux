<?php

namespace App\Form;

use App\Entity\ListType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
class Listform extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre')
            ->add('Description')
            ->add('Couverture')
            ->add('theme', ChoiceType::class, [
                'choices' => [
                    'Anniversaire' => 'anniversaire',
                    'Mariage' => 'mariage',
                    'Naissance' => 'naissance',
                    'Baptême' => 'baptême',
                    'Pot de départ' => 'pot_de_départ',
                    'Crémaillère' => 'crémaillère',
                    // Add other theme options here
                ],
                'placeholder' => '', // Optional: Add a placeholder
            ])
            ->add('status', CheckboxType::class, [
                'label' => 'Privée',
                'required' => false, // Mettez à true si vous voulez que la case soit cochée par défaut
            ])-> add('password', null, [
        'attr' => [
            'readonly' => true,
        ],
    ])
            ->add('date_ouvert')
            ->add('date_fin')
            -> add('user_id', HiddenType::class, [
                'attr' => [
                    'style' => 'display:none',
                ],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ListType::class,
        ]);
    }
}
