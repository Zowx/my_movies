<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditMovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du film',
            ])
            ->add('dateRelease', DateType::class, [
                'label' => 'Date de réalisation',
            ])
            ->add('type', TextType::class)

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
            ])

            ->add('ajouterFilm', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-black'
                ],
                'label' => 'Enregistrer',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
