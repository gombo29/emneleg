<?php

namespace happy\CmsBundle\Form\SearchForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentSearchType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', 'text', array(
                    'label' => 'Гарчиг',
                    'required' => false
                )
            )

            ->add('ehlehDate', 'datetime', array(
                'required' => false,
                'label' => 'Эхлэл /Үүсгэсэн огнооноос хайна/',
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => [ 'datetime' => 'picker']
            ))

            ->add('duusahDate', 'datetime', array(
                'required' => false,
                'label' => 'Төгсгөл /Үүсгэсэн огнооноос хайна/',
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => [ 'datetime' => 'picker']
            ));
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
