<?php

namespace happy\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OnlineDoctorQuestionType extends AbstractType
{
    public $type;

    public function __construct($type)
    {
        $this->type = $type;
    }


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('descr', 'textarea', array(
                    'label' => 'Асуулт / Хариулт',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('childYes', 'entity', array(
                'class' => 'happyCmsBundle:OnlineDoctorQuestion',
                'label' => 'Тийм хариулт',
                'property' => 'descr',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pp')
                        ->where('pp.type = :type')
                        ->setParameter('type' , $this->type);
                },
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('childNo', 'entity', array(
                'class' => 'happyCmsBundle:OnlineDoctorQuestion',
                'label' => 'Үгүй хариулт',
                'property' => 'descr',
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pp')
                        ->where('pp.type = :type')
                        ->setParameter('type' , $this->type);
                },
                'attr' => array(
                    "class" => "form-control",
                )
            ))


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
            )
            ->add('imagefile', 'file', array(
                'label' => 'Бүдүүвч зураг оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('imagefile2', 'file', array(
                'label' => 'Бүдүүвч зураг №2 оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))
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
