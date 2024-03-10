<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du film',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('dateRelease', DateType::class, [
                'label' => 'Date de réalisation',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('type', TextType::class, [
                'label' => 'Type',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('synopsis', TextareaType::class, [
                'label' => 'Synopsis',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 5,
                    'cols' => 50
                ]
            ])
            ->add('realisator', TextType::class, [
                'label' => 'Réalisateur',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])

            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'attr' => [
                    'class' => 'form-control-file',
                    'placeholder' => 'Choisissez un fichier'
                ]
            ])

            ->add('ajouterFilm', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-black'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}