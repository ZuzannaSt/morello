<?php

namespace AppBundle\Form\Admin;

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
            && !in_array('project-delete', $options['validation_groups'])
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
                    'required' => true
                )
            );
            $builder->add(
                'firstName',
                'text',
                array(
                    'required' => true
                )
            );
            $builder->add(
                'lastName',
                'text',
                array(
                    'required' => true
                )
            );
            $builder->add(
                'roles',
                'entity',
                array(
                    'class' => 'AppBundle:Role',
                    'property' => 'name',
                    'multiple' => true
                )
            );
            $builder->add(
                'project',
                'entity',
                array(
                    'class' => 'AppBundle:Project',
                    'property' => 'name',
                    'multiple' => true
                )
            );
            $builder->add(
                'save',
                'submit',
                array(
                    'label' => 'Dodaj użytkownika'
                )
          );
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    public function getName()
    {
        return 'admin_user_form';
    }
}
