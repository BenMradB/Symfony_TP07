<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Enter The Article Name ...'
                ],
                'label' => false
            ])
            ->add('price', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Enter The Article Price ...'
                ],
                'label' => false
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'attr' => [
                    'class' => 'form-select mb-3 mt-2'
                ],
                'label' => 'Category'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
