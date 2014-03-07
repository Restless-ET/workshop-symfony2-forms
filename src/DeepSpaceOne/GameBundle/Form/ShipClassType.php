<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DeepSpaceOne\GameBundle\Form;

use Symfony\Component\Validator\Constraints\True;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ShipClassType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('crewSize')
            ->add('equipment', 'integer', array('property_path' => 'equipmentCapacity'))
            ->add('payload', 'integer', array('property_path' => 'payloadCapacity'))
            ->add('price')
        ;

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\DeepSpaceOne\GameBundle\Entity\ShipClass',
        ));
    }

    public function getName()
    {
        return 'deepspaceone_shipclass';
    }
}
