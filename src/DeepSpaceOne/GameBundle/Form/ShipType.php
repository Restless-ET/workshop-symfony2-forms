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
            ->add('class', null, array(
                'attr' => array(
                    'help_text' => 'The ship class determines the allowed number of mountpoints and the amount of payload the ship can carry.'
                ),
            ))
            ->add('updateClass', 'submit', array('validation_groups' => false))
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

            $factory = $form->getConfig()->getFormFactory();

            $form->add(
                    // MountPoints Builder
                    $factory->createNamedBuilder('mountPoints', 'bootstrap_collection', null, array(
                        'type' => new MountPointType(),
                        //'allow_add' => true,
                        //'allow_delete' => true,
                        'auto_initialize' => false, // Automatic initialization is only supported on root forms.
                                                    // You should set the "auto_initialize" option to false on the field "mountPoints".
                    ))
                    ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
                        $mountPointsForm = $event->getForm();
                        $data = $event->getData();

                        //var_dump($event->getData());
                        //var_dump(count($mountPointsForm->all())); // already correct the count
                        //die;

                        // Prevent "extra fields" errors
                        foreach ($data as $index => $value) {
                            if (!$mountPointsForm->has($index)) {
                                unset($data[$index]);
                            }
                        }

                        $event->setData($data);
                    })
                    ->getForm()
                )
                ->add('payload', 'bootstrap_collection', array(
                    'type' => new PayloadType(),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'error_bubbling' => false,
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
            'data_class' => 'DeepSpaceOne\GameBundle\Entity\Ship',
            'error_mapping' => array(
                'isPayloadTooHeavy' => 'payload'
            )
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
