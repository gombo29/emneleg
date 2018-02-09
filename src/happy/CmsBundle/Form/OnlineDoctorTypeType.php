<?php

namespace happy\CmsBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OnlineDoctorTypeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parent', 'entity', array(
                'label' => 'Ангилал',
                'class' => 'happy\CmsBundle\Entity\OnlineDoctorType',
                'property' => 'name',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pp')
                        ->where('pp.parent is null');
                },
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

            ->add('isFinish', 'choice',
                array(
                    'label' => 'Веб дээр харуулах эсэх',
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

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\OnlineDoctorType'
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
