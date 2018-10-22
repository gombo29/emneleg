<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NurseType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('doctorPosId', 'entity', array(
                'class' => 'happyCmsBundle:DoctorPosition',
                'label'=>'Байршил нэр',
                'property' => 'name',
                'required' => false,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('name', 'text', array(
                    'label' => 'Нэр',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('phone', 'text', array(
                    'label' => 'Утас',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('imagefile', 'file', array(
                'label' => 'Зураг оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('namtar', 'textarea', array(
                    'label' => 'Намтар',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('turshlaga', 'textarea', array(
                    'label' => 'Туршлага',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('uzlegTorol', 'text', array(
                    'label' => 'Үзлэг төрөл',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('surguuli', 'text', array(
                    'label' => 'Төгссөн сургууль',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => "Жишээ: Анагаах;Harvard"
                    )
                )
            )
            ->add('mergeshil', 'text', array(
                    'label' => 'Мэргэшил',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('timeTable', 'text', array(
                    'label' => 'Цагийн хуваарь',
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
