<?php

/**
 * BoardType Form
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Form;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class ProjectType
 * @package AppBundle\Form
 */
class ProjectType extends AbstractType
{
    /**
     * Build entity form
     *
     * @param builder, options
     */
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
                'name',
                'text',
                array(
                    'label'       => 'Nazwa projektu',
                    'required'    => true,
                    'max_length'  => 128
                )
            );
            $builder->add(
                'description',
                'textarea',
                array(
                    'label'       => 'Opis projektu',
                    'required'    => false,
                    'max_length'  => 256
                )
            );
            $builder->add(
                'users',
                'entity',
                array(
                    'multiple' => true,
                    'property' => 'username',
                    'class'    => 'AppBundle:User',
                    'label'    => 'Użytkownicy',
                    'required' => false
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
     * @param resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Project',
                'validation_groups' => 'projects-add',
              )
        );
    }

    /**
     * Get name
     *
     * @return project
     */
    public function getName()
    {
        return 'project';
    }
}
