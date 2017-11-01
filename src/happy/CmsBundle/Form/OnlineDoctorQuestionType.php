<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OnlineDoctorQuestionType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'label' => 'Асуулт',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('descr', 'textarea', array(
                    'label' => 'Тайлбар',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('isFirst', 'choice',
                array(
                    'label' => 'Эхнийх эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isLast', 'choice',
                array(
                    'label' => 'Сүүлчийнх эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\OnlineDoctorQuestion'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_content';
    }
}
