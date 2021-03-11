<?php

namespace App\Form;

use App\Entity\Channel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChannelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ChannelName')
            ->add('ChannelDescription',TextareaType::class)
            ->add('ChannelStatus' , ChoiceType::class,[
                    'choices'=>['Active'=>'1','Banned'=>'0'
                ]]
            )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Channel::class,
        ]);
    }
}
