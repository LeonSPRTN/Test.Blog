<?php

namespace App\Form;

use App\Entity\Articles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array(
                'label'=> 'Наименование: '
            ))
            ->add('Headline', TextType::class, array(
                'label'=> 'Заголовок: '
            ))
            ->add('ArticleText', TextareaType::class, array(
                'label'=> 'Текст: '
            ))
            ->add('Date', DateType::class, array(
                'label'=> 'Дата: '
            ))
            ->add('Category', null, array(
                'label'=> 'Категория: '
            ));
            //->add('Add', SubmitType::class, array('label'=>'Добавить'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
