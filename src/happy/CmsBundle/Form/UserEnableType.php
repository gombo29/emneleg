<?php

namespace happy\CmsBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use happy\CmsBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEnableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('enabled', 'choice',
                array(
                    'label' => 'Хандах эрх өөрчлөх',
                    'choices' => array(
                        '1' => 'Нээх',
                        '0' => 'Хаах',
                    ),
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('save', 'submit', array(
                'label' => 'Хадгалах',
                'attr' => array('class' => 'btn btn-success')
            ));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\User',
            'em' => null,
        ));
    }
}
