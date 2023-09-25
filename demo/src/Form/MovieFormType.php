<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Movie Name',
            ])
            ->add('image', FileType::class, [
                'label' => 'Movie Image',
                'mapped' => false,
                'required' => false,
            ])
            ->add('director', TextType::class, [
                'label' => 'Director',
            ])
            ->add('budget', TextType::class, [
                'label' => 'Budget',
            ])
            ->add('crew', TextType::class, [
                'label' => 'Crew',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 4,
                ],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Category',
                'choices' => [
                    'Action' => 'Action',
                    'Comedy' => 'Comedy',
                    'Drama' => 'Drama',
                    'Thriller' => 'Thriller',
                    'Romance' => 'Romance',
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
