<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DeepSpaceOne\GameBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class FormTypeBootstrapExtension extends AbstractTypeExtension
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // Only set the style variable for root forms or if it is explicitly
        // set
        if (null !== $options['style']) {
            $view->vars['style'] = $options['style'];
        } elseif (!$view->parent) {
            $view->vars['style'] = 'horizontal';
        }

        $view->vars['label_col'] = 2;
        $view->vars['widget_col'] = 6;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'style' => null,
        ));

        // null means that the style set in the template will be used
        $resolver->setAllowedValues(array(
            'style' => array(null, 'none', 'horizontal', 'inline'),
        ));
    }

    public function getExtendedType()
    {
        return 'form';
    }
}
