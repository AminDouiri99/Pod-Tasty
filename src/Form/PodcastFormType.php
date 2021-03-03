<?php

namespace App\Form;

use App\Entity\Podcast;
use Doctrine\DBAL\Types\TextType;
use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PodcastFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('PodcastName',TextType::class,[
                'label'=>'Nom Podcast',
                'attr'=>[
                    'placeholder'=>'Merci de définir le nom',
                    'class'=>'PodcastName'
                ]])
            ->add('PodcastDescription',TextType::class,[
                'label'=>'Description',
                'attr'=>[
                    'placeholder'=>'Description',
                    'class'=>'PodcastDescription'
              ]])
            ->add('PodcastImage',filetype())
            ->add('PodcastGenre',sumbitType::class)
            ->add('PodcastViews')
            ->add('PodcastDate',DataType::class)
            ->add('PodcastSource',filetype())
            ->add('PlaylistId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Podcast::class,
        ]);
    }
}
