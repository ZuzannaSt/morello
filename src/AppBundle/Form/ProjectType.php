<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'hidden'
        )
        ->add(
            'name',
            'text'
        )
        ->add(
            'description',
            'textarea'
        )
        ->add(
            'users',
            'entity',
            array(
                'multiple' => true,
                'expanded' => true,
                'property' => 'username',
                'class'    => 'AppBundle:User'
            )
        )
        ->add(
            'save',
            'submit',
            array(
                'label' => 'Save'
            )
        );
    }

    public function getName()
    {
        return 'project_form';
    }
}
