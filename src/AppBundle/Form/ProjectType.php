<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            'text',
            array(
                'label'       => 'Project Name',
                'required'    => true,
                'max_length'  => 128
            )
        )
        ->add(
            'description',
            'textarea',
            array(
                'label'       => 'Project Description',
                'required'    => false,
                'max_length'  => 256
            )
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Project'
              )
        );
    }

    public function getName()
    {
        return 'project';
    }
}
