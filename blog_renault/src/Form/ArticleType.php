<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required' => true, 'label' => 'Titre'])
            ->add('content', TextareaType::class, ['required' => true, 'label' => 'Contenu'])
            ->add('author', TextType::class, ['label' => 'Auteur',
                'required' => false,
                'empty_data' => null])
            ->add('nb_views', NumberType::class, ['empty_data' => 1, 'label' => 'Nombre de vues'])
            ->add('published', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                    ],
                'label' => 'Publié'
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
