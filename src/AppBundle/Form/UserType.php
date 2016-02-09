<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * Build entity form
     *
     * @param builder, options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('required' => true))
            ->add('username', 'text', array('required' => true))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'first_options'  => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'),
                ))
            ->add(
                'firstName',
                'text',
                array(
                  'required' => false
                )
            )
            ->add(
                'lastName',
                'text',
                array(
                    'required' => false
                )
            )
          ->add(
              'save',
              'submit',
              array(
                  'label' => 'Zarejestruj się'
              )
          );
    }

    /**
     * Set default options
     *
     * @param resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * Get name
     *
     * @return user
     */
    public function getName()
    {
        return 'user';
    }
}
