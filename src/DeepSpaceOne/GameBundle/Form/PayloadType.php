<?php

namespace DeepSpaceOne\GameBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PayloadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('mass', null, array(
                //'data' => 5 // This will override what comes from the DB on edit action
            ))
            ->add('good')
        ;

        //$builder->get('mass')->setData(10);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'DeepSpaceOne\GameBundle\Entity\Payload',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'deepspaceone_payload';
    }
}
