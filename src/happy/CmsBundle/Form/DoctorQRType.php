<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DoctorQRType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('doctorType', 'entity', array(
                'class' => 'happyCmsBundle:DoctorType',
                'label'=>'Үйлчилгээний төрөл нэр',
                'property' => 'name',
                'required' => false,
            ))

            ->add('doctorPosition', 'entity', array(
                'class' => 'happyCmsBundle:DoctorPosition',
                'label'=>'Байршил нэр',
                'property' => 'name',
                'required' => false,
            ))

            ->add('imagefile', 'file', array(
                'label' => 'QR оруулах',
                'required' => true,
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
            'data_class' => 'happy\CmsBundle\Entity\DoctorQpay'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_doctor';
    }
}
