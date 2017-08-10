<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('contentType', 'entity', array(
                'label' => 'Зөвлөгөө төрөл',
                'class' => 'happy\CmsBundle\Entity\ContentType',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('name', 'text', array(
                    'label' => 'Гарчиг',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('imagefile', 'file', array(
                'label' => 'Зураг оруулах',
                'required' => true,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('headline', 'textarea', array(
                    'label' => 'Товч тайлбар',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('describe', 'textarea', array(
                    'label' => 'Тайлбар',
                    'required' => true,
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
            'data_class' => 'happy\CmsBundle\Entity\Content'
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
