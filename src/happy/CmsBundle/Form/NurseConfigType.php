<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NurseConfigType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('isShow', 'choice',
                array(
                    'label' => 'Харагдуулах эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
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
            'data_class' => 'happy\CmsBundle\Entity\Doctors'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_nurse';
    }
}
