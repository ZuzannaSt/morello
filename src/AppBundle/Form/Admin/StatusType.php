<?php

namespace AppBundle\Form\Admin;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class StatusType extends AbstractType
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
        {
            $builder->add(
                'name',
                'text',
                array(
                    'label'       => 'Nazwa statusu',
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
     * @param resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Status',
                'validation_groups' => 'status-add',
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
        return 'status';
    }
}
