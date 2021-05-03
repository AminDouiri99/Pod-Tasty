<?php

namespace App\Form;

use App\Entity\Podcast;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;


class PodcastType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PodcastName',TextType::class)
            ->add('PodcastDescription',TextType::class)
            ->add('PodcastImage',FileType::class, array(
                'mapped' => false,
                'required' => false
            ))
            ->add('tags',TextType::class)
            ->add('PlaylistId',EntityType::class, array(
                'class' => 'App\Entity\Playlist',
                'choice_label' => 'PlaylistName'
            ))

        //->add('PodcastViews')
//            ->add('PodcastDate',DateType::class,array(
//                'widget' => 'single_text',
//                'format' => 'yyyy-MM-dd',
//            ))
                //array('input'  => 'datetime','widget' => 'choice', 'attr' => array('class' =>'calendar')))

            ->add('PodcastSource',FileType::class,array(
                    'mapped' => false,
                    'required' => true,
                ))

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
