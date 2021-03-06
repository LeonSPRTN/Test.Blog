<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('id', NumberType::class, array(
                'required'=> false
            ))
            ->add('Name', TextType::class, array(
                'label'=> 'Наименование: '
            ))
            ->add('Headline', TextType::class, array(
                'label'=> 'Заголовок: '
            ))
            ->add('ArticleText', TextareaType::class, array(
                'label'=> 'Текст: '
            ))
            ->add('Date', DateTimeType::class, array(
                'label'=> 'Дата: '
            ))
            ->add('Category', null, array(
                'label'=> 'Категория: '
            ));
    }

    public function configureOptions(OptionsResolver $resolver):void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
