<?php

namespace App\Form;

use App\Entity\Reclamation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $choices = [
            'Sexual content' => 'rb1',
            'Violent or repulsive content' => 'rb2',
            'Hateful or abusive content' => 'rb3',
            'Harmful or dangerous acts' => 'rb4',
            'Child abuse' => 'rb5',
            'Promotes terrorism' => 'rb6',
            'Spam or misleading' => 'rb7',
            'Infringes my rights' => 'rb8',
            'Captions issue' => 'rb9',
        ];
        $builder
            ->add('Type',ChoiceType::class,[
            'choices' => $choices,
            ])
            ->add('Description',TextareaType::class)
            ->add('Status')
            ->add('PodcastId')
            ->add('UserId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
