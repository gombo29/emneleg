<?php

namespace happy\CmsBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use happy\CmsBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Нэвтрэх нэр',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))
            ->add('email', 'email', array(
                'label' => 'Цахим шуудан',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'invalid_message' => 'The password fields must match.',
                'first_options' => array('label' => 'Нууц үг', 'attr' => array(
                    "class" => "form-control",
                )),
                'second_options' => array('label' => 'Нууц үг давт', 'attr' => array(
                    "class" => "form-control",
                )),

            ))
            ->add('roles', 'entity', array(
                'label' => 'хандах эрх',
                'class' => 'happy\CmsBundle\Entity\Role',
                'required' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pp')
                        ->andWhere('pp.name <> :name')
                        ->setParameter('name', 'ROLE_USER');

                },
                'property' => 'disname',
                'expanded' => true,
                'multiple' => true,
                'data' => $this->getRoles($options['em'], $builder->getData()),
            ));
    }

    /**
     * @param EntityManagerInterface $em
     * @return array
     */
    private function getRoles(EntityManagerInterface $em, User $user)
    {
        if (!$em || !$user) return array();

        return $em->getRepository('happyCmsBundle:Role')->createQueryBuilder('e')
            ->andWhere('e.name in (:roles)')
            ->setParameter('roles', $user->getRoles())
            ->getQuery()
            ->getResult();
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
