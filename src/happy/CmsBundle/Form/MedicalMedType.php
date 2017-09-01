<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicalMedType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('name', 'text', array(
                    'label' => 'Нэр',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('imagefile', 'file', array(
                'label' => 'Web зураг оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('imageActivefile', 'file', array(
                'label' => 'Active зураг оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('imageMobilefile', 'file', array(
                'label' => 'Mobile зураг оруулах',
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
            'data_class' => 'happy\CmsBundle\Entity\MedicalType'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_med_type';
    }
}
