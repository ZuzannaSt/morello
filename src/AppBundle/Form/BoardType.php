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
 * Class BoardType
 * @package AppBundle\Form
 */
class BoardType extends AbstractType
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

    /**
     * Set default options
     *
     * @param OptionsResolverInterface resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'AppBundle\Entity\Board',
                'validation_groups' => 'boards-add',
              )
        );
    }

    /**
     * Get name
     *
     * @return board
     */
    public function getName()
    {
        return 'board';
    }
}
