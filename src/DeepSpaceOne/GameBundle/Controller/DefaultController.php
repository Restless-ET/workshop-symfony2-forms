<?php

namespace DeepSpaceOne\GameBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controller for handling the default route.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('ships'));
    }
}
