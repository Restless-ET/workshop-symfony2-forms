<?php

namespace DeepSpaceOne\GameBundle\Form;

use Symfony\Component\Form\FormEvents;

use Symfony\Component\Form\FormEvent;
use DeepSpaceOne\GameBundle\Entity\Ship;

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
            ->add('name', null, array('required' => false))
            ->add('class')
        ;

        // Make sure that the form has a default ship so that the correct number
        // of mount points can be determined.
        if (null === $builder->getData()) {
            $classChoices = $builder->get('class')->getOption('choice_list')->getChoices();
            $defaultClass = reset($classChoices);

            $builder->setData(new Ship($defaultClass));
        }

        $updateClass = function (FormEvent $event) {
            $form = $event->getForm()->getParent();
            $class = $event->getForm()->getData();
            $ship = $form->getData();

            // Apply the update manually. The automatic update only takes place
            // after adding all children.
            $ship->setClass($class);

            $form->add('mountPoints', 'bootstrap_collection', array(
                    'type' => new MountPointType(),
                    //'allow_add' => true,
                    //'allow_delete' => true,
                ))
                ->add('payload', 'bootstrap_collection', array(
                    'type' => new PayloadType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                ));
        };

        $builder->get('class')->addEventListener(FormEvents::POST_SET_DATA, $updateClass);
        $builder->get('class')->addEventListener(FormEvents::POST_SUBMIT, $updateClass);
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
