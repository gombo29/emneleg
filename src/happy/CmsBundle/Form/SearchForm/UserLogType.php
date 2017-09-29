<?php

namespace happy\CmsBundle\Form\SearchForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserLogType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('adminname', 'text', array(
                    'label' => 'Админ нэр',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )

            ->add('medId', 'text', array(
                    'label' => 'Эмнэлэг ID',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
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
            'data_class' => 'happy\CmsBundle\Entity\UserLogs'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_project';
    }
}
