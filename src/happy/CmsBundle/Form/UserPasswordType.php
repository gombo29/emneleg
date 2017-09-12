<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'Нууц үг таарсангүй.',
                'first_options' => array('label' => 'Нууц үг', 'attr' => array(
                    "class" => "form-control")),
                'second_options' => array('label' => 'Нууц үг давт', 'attr' => array(
                    "class" => "form-control")),
            ))
            ->add('save', 'submit', array(
                'label' => 'Хадгалах',
                'attr' => array('class' => 'btn btn-success', "style" => "margin-top:10px")
            ))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\User'
        ));
    }
}
