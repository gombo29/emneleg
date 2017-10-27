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

            ->add('type', 'entity', array(
                'label' => 'Ангилал',
                'class' => 'happy\CmsBundle\Entity\OnlineDoctorType',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('parentYes', 'entity', array(
                'label' => 'Тийм',
                'class' => 'happy\CmsBundle\Entity\OnlineDoctorQuestion',
                'property' => 'name',
                'required' => false,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('parentNo', 'entity', array(
                'label' => 'Үгүй',
                'class' => 'happy\CmsBundle\Entity\OnlineDoctorQuestion',
                'property' => 'name',
                'required' => false,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('name', 'text', array(
                    'label' => 'Асуулт',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
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
            )

            ->add('descr', 'textarea', array(
                    'label' => 'Тайлбар',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
           ;
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
