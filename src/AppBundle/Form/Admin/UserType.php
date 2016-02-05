<?php

namespace AppBundle\Form\Admin;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('user-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'email',
                'email',
                array(
                    'required' => true
                )
            );
            $builder->add(
                'plainPassword',
                'repeated',
                array(
                    'type' => 'password',
                    'first_options'  => array('label' => 'Hasło'),
                    'second_options' => array('label' => 'Powtórz hasło'),
                )
            );
            $builder->add(
                'username',
                'text',
                array(
                    'required' => true,
                    'label'    => 'Nazwa użytkownika'
                )
            );
            $builder->add(
                'firstName',
                'text',
                array(
                    'required' => true,
                    'label'    => 'Imię'
                )
            );
            $builder->add(
                'lastName',
                'text',
                array(
                    'required' => true,
                    'label'    => 'Nazwisko'
                )
            );
            $builder->add(
                'roles',
                'entity',
                array(
                    'class'    => 'AppBundle:Role',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => true,
                    'label'    => 'Role'
                )
            );
            $builder->add(
                'projects',
                'entity',
                array(
                    'class'    => 'AppBundle:Project',
                    'property' => 'name',
                    'multiple' => true,
                    'required' => false,
                    'label'    => 'Projekty'
                )
            );
        }
        $builder->add(
            'save',
            'submit',
            array(
                'label' => 'Zapisz'
            )
        );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\User',
                'validation_groups' => 'user-add',
            )
        );
    }

    public function getName()
    {
        return 'admin_user_form';
    }
}
