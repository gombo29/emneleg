<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('name', 'text', array(
                    'label' => 'Овог нэр',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('phone', 'text', array(
                    'label' => 'Утасны дугаар',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('imagefile', 'file', array(
                'label' => false,
                'required' => false,
            ))
            ->add('namtar', 'textarea', array(
                    'label' => 'Боловсролын товч намтар',
                    'required' => false,
                    'attr' => array(
                        "class" => "md-textarea",
                    )
                )
            )
            ->add('turshlaga', 'textarea', array(
                    'label' => 'Ажлын туршлага',
                    'required' => false,
                    'attr' => array(
                        "class" => "md-textarea",
                    )
                )
            )
            ->add('surguuli', 'text', array(
                    'label' => 'Төгссөн сургууль',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('mergeshil', 'text', array(
                    'label' => 'Мэргэшсэн чиглэл',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('timeTable', 'text', array(
                    'label' => 'Боломжит цагийн хуваарь',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('isRequire', CheckboxType::class, array(
                'label' => 'Ажлын нөхцөлийг зөвшөөрч байна ',
                'required' => true,
                'label_attr'=> array('style' => 'font-size: 16px;')
            ));
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
