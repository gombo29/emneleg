<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BannerType extends AbstractType
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

            ->add('bannerbairlal', 'entity', array(
                'label' => 'Banner байрлал',
                'class' => 'happy\CmsBundle\Entity\BannerPosition',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))


            ->add('imagefile', 'file', array(
                'label' => 'Зураг оруулах',
                'required' => true,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('publishDate', 'datetime', array(
                'label'=> 'Нийтлэх огноо',
                'required' => true,
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => array(
                    "class" => "form-control",
                    'datetime' => 'picker'
                )
            ))
            ->add('endDate', 'datetime', array(
                'label'=> 'Хаах огноо',
                'required' => true,
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => array(
                    "class" => "form-control",
                    'datetime' => 'picker'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\Banner'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_banner';
    }
}
