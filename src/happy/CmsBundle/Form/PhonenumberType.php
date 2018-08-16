<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhonenumberType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('phoneNumber', 'text', array(
                    'label' => 'Утасны дугаар',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            );

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\Phonenumber'
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
