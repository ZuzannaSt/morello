<?php

/**
 * RoleType Form
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Form\Admin;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class RoleType
 * @package AppBundle\Form\Admin
 */
class RoleType extends AbstractType
{
    /**
     * Build entity form
     *
     * @param FormBuilderInterface builder
     * @param array options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'hidden'
        );
        {
            $builder->add(
                'name',
                'text',
                array(
                    'label'       => 'Nazwa roli',
                    'required'    => true,
                    'max_length'  => 128
                )
            );
            $builder->add(
                'role',
                'text',
                array(
                    'label'       => 'ROLA_NAZWA',
                    'required'    => true,
                    'max_length'  => 128
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

    /**
     * Set default options
     *
     * @param OptionsResolverInterface resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Role',
                'validation_groups' => 'roles-add',
              )
        );
    }

    /**
     * Get name
     *
     * @return user
     */
    public function getName()
    {
        return 'role';
    }
}
