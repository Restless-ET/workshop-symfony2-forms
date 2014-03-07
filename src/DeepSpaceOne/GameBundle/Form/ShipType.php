<?php

namespace DeepSpaceOne\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShipType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('class')
            ->add('mountPoints', 'bootstrap_collection', array(
                'type' => new MountPointType(),
                'allow_add' => true,
                'allow_delete' => true,
            ))
            ->add('payload', 'bootstrap_collection', array(
                'type' => new PayloadType(),
                'allow_add' => true,
                'allow_delete' => true,
            ))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DeepSpaceOne\GameBundle\Entity\Ship'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'deepspaceone_ship';
    }
}
