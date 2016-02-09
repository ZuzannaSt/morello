<?php

namespace AppBundle\Form;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BoardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'id',
            'hidden'
        );
        if (isset($options['validation_groups'])
            && count($options['validation_groups'])
            && !in_array('board-delete', $options['validation_groups'])
        ) {
            $builder->add(
                'name',
                'text',
                array(
                    'label'       => 'Nazwa tablicy',
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Board',
                'validation_groups' => 'boards-add',
              )
        );
    }

    public function getName()
    {
        return 'board';
    }
}
