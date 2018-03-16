<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MedicalType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'label' => 'Эмнэлгийн нэр',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('nameLat', 'text', array(
                    'label' => 'Эмнэлгийн нэр /Латин/',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('headline', 'textarea', array(
                    'label' => 'Тайлбар',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => "height: 300px;"
                    )
                )
            )

            ->add('imagefile', 'file', array(
                'label' => 'Нүүр зураг оруулах',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('address', 'text', array(
                    'label' => 'Эмнэлгийн хаяг',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('phone', 'text', array(
                    'label' => 'Эмнэлгийн утас',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => 'Жишээ: 99031675;99999999;99040505'
                    )
                )
            )

            ->add('email', 'text', array(
                    'label' => 'Эмнэлгийн Email',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('fbAddress', 'text', array(
                    'label' => 'Эмнэлгийн FB хаяг',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => "Жишээ: https://www.facebook.com/Emnelegmn-103407000305985/"
                    )
                )
            )
            ->add('website', 'text', array(
                    'label' => 'Эмнэлгийн вебсайт',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => "Жишээ: http://www.emneleg.mn"

                    )
                )
            )
            ->add('busStation', 'text', array(
                    'label' => 'Эмнэлгийн ойролцоо буудлын нэр',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => "Жишээ: Багшийн дээд"
                    )
                )
            )
            ->add('isParking', 'choice',
                array(
                    'label' => 'Эмнэлэг гадаа зогсоолтой эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('parkingPrice', 'text', array(
                    'label' => 'Эмнэлгийн зогсоолын үнэ',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('isCard', 'choice',
                array(
                    'label' => 'Эмнэлэг төлбөрийн картаар үйлчилдэг эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isWifi', 'choice',
                array(
                    'label' => 'Эмнэлэг Wifi-тай эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isTasag', 'choice',
                array(
                    'label' => 'Эмнэлэг хэвтэн эмчлүүлэх тасагтай эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('hoolTotal', 'text', array(
                    'label' => 'Эмнэлэг өдөрт хэдэн удаа хоол өгдөг вэ?',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('isSaturdayHool', 'choice',
                array(
                    'label' => 'Эмнэлэг хагас сайнд хоол өгдөг эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isSundayHool', 'choice',
                array(
                    'label' => 'Эмнэлэг бүтэн сайнд хоол өгдөг эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isDaatgal', 'choice',
                array(
                    'label' => 'Эмнэлэгт хэвтэн эмчлүүлэхэд даатгалын хөнгөлөлт эдлэх эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('isLaboratory', 'choice',
                array(
                    'label' => 'Эмнэлэг үзлэг оношилгоо хийдэг эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
                    )
                )
            )
            ->add('timeTable', 'text', array(
                    'label' => "Цагийн хуваарь",
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => "Жишээ: Даваа-баасан: 09:00-17:00; Бямба: 09:00-13:00; Ням: Амарна"
                    )

                )
            )
            ->add('longLat', 'text', array(
                    'label' => "Байршил",
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    ),
                    'read_only' => true
                )

            )
            ->add('isDoctor', 'choice',
                array(
                    'label' => 'Эмчийн үзлэг хийдэг эсэх',
                    'choices' => array(
                        '1' => 'Тийм',
                        '0' => 'Үгүй'
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control col-md-2 col-lg-3",
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
            'data_class' => 'happy\CmsBundle\Entity\Medicals'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_medical';
    }
}
