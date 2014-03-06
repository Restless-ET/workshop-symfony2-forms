<?php

namespace DeepSpaceOne\GameBundle\Controller;

use DeepSpaceOne\GameBundle\Entity\ShipClass;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Handles the ship class CRUD operations.
 *
 * @Route("/classes")
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ShipClassController extends Controller
{
    /**
     * Lists all ShipClass entities.
     *
     * @Route("/", name="classes")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $classes = $em->getRepository('DeepSpaceOneGameBundle:ShipClass')->findAll();

        return array(
            'classes' => $classes,
        );
    }

    /**
     * Displays a form to create a new ShipClass entity.
     *
     * @Route("/new", name="classes_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        // TASK 2: create form

        return array(
            // TASK 2: pass form to view
        );
    }

    /**
     * Creates a new ShipClass entity.
     *
     * @Route("/", name="classes_create")
     * @Method("POST")
     * @Template("DeepSpaceOneGameBundle:ShipClass:new.html.twig")
     */
    public function createAction(Request $request)
    {
        // TASK 2: create form and handle the request

        // TASK 2: update if condition
        if (false) {
            // TASK 2: create class

            $em = $this->getDoctrine()->getManager();
            // TASK 2: persist class
            $em->flush();

            return $this->redirect($this->generateUrl('classes'));
        }

        return array(
            // TASK 2: pass form to view
        );
    }

    /**
     * Displays a form to edit an existing ShipClass entity.
     *
     * @Route("/{id}/edit", name="classes_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(ShipClass $class)
    {
        $deleteForm = $this->createDeleteForm($class);

        // TASK 2: create edit form for $class

        return array(
            'class' => $class,
            'delete_form' => $deleteForm->createView(),
            // TASK 2: pass form to view
        );
    }

    /**
     * Edits an existing ShipClass entity.
     *
     * @Route("/{id}", name="classes_update")
     * @Method("PUT")
     * @Template("DeepSpaceOneGameBundle:ShipClass:edit.html.twig")
     */
    public function updateAction(Request $request, ShipClass $class)
    {
        $deleteForm = $this->createDeleteForm($class);

        // TASK 2: create edit form and handle the request

        // TASK 2: update if condition
        if (false) {
            // TASK 2: update $class entity

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('classes'));
        }

        return array(
            'class' => $class,
            'delete_form' => $deleteForm->createView(),
            // TASK 2: pass form to view
        );
    }

    /**
     * Finds and displays a ShipClass entity.
     *
     * @Route("/{id}", name="classes_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(ShipClass $class)
    {
        $deleteForm = $this->createDeleteForm($class);

        return array(
            'class' => $class,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a ShipClass entity.
     *
     * @Route("/{id}", name="classes_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ShipClass $class)
    {
        $form = $this->createDeleteForm($class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($class);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('classes'));
    }

    /**
     * Creates a form to delete a ShipClass entity by id.
     *
     * @param ShipClass $class The ship class
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(ShipClass $class)
    {
        return $this->createFormBuilder(null, array('style' => 'none'))
            ->setAction($this->generateUrl('classes_delete', array('id' => $class->getId())))
            ->setMethod('DELETE')
            ->add('delete', 'submit', array('attr' => array('class' => 'btn-danger')))
            ->getForm()
        ;
    }
}
