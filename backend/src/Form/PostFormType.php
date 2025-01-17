<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\DBAL\Types\StringType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'The post title is required',
                    ]),
                    new Length([
                        'min' => 1,
                        'minMessage' => 'The post title must be at least 1 characters',
                        'max' => 100,
                        'maxMessage' => 'The post title be no more than 100 characters long',
                    ]),
                ],

            ])
            ->add('body', TextareaType::class, [
                'required' => true,
                'attr' => ['rows' => 8],
                'constraints' => [
                    new NotBlank([
                        'message' => 'The post body is required'
                    ]),
                    new Length([
                        'min' => 20, 
                        'minMessage' => 'Post body must be at least 20 characters',
                        'max' => 2000, 
                        'maxMessage' => 'Post body be no more than 2000 characters long',
                    ])
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '100k',
                        'mimeTypes' => [
                            'image/*'
                        ],
                    ],
                    maxSizeMessage: 'File be no more than 100kb',
                    mimeTypesMessage: 'File type must be an image format png, jpg, jpeg, webp',
                    )  
                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'required' => true,
                'choice_label' => 'title',
                'placeholder' => 'Choose category'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
