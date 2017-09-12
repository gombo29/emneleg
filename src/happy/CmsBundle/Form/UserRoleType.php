<?php

namespace happy\CmsBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use happy\CmsBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', 'entity', array(
                'class' => 'happy\CmsBundle\Entity\Role',
                'property' => 'disname',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('pp')
                        ->andWhere('pp.name <> :name')
                        ->setParameter('name', 'ROLE_USER');

                },
                'expanded' => true,
                'multiple' => true,
                'data' => $this->getRoles($options['em'], $builder->getData())
            ))
            ->add('save', 'submit', array(
                'label'=>'хадгалах',
                'attr' => array('class' => 'btn btn-success', 'style' => 'margin-top:10px')
            ));
    }

    /**
     * @param EntityManagerInterface $em
     * @param User $user
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
