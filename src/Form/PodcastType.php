<?php

namespace App\Form;

use App\Entity\Podcast;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PodcastName',TextType::class,[
                'label'=>'Nom de Podcast :',

            ])
            ->add('PodcastDescription',TextType::class,[
                'label'=>'Description :'
            ])
            ->add('PodcastImage',FileType::class, array(
                'label'=>'Image de Podcast :',
                'mapped' => false,
                'required' => false
            ))
            //->add('PodcastGenre')
            //->add('PodcastViews')
            ->add('PodcastDate',DateType::class,[
                'label'=>'Date :',
            ])
                //array('input'  => 'datetime','widget' => 'choice', 'attr' => array('class' =>'calendar')))

            ->add('PodcastSource',FileType::class,[
                'label'=>'Podcast :'
            ])
            //->add('PlaylistId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
