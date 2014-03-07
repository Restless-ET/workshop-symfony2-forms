<?php

namespace DeepSpaceOne\GameBundle\Controller;

use DeepSpaceOne\GameBundle\Form\ShipType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DeepSpaceOne\GameBundle\Entity\Ship;

/**
 * Handles the ship CRUD operations.
 *
 * @Route("/ships")
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ShipController extends Controller
{
    /**
     * Lists all Ship entities.
     *
     * @Route("/", name="ships")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $ships = $em->getRepository('DeepSpaceOneGameBundle:Ship')->findAll();

        return array(
            'ships' => $ships,
        );
    }

    /**
     * Displays a form to create a new Ship entity.
     *
     * @Route("/new", name="ships_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $form = $this->createCreateForm();

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Ship entity.
     *
     * @Route("/", name="ships_create")
     * @Method("POST")
     * @Template("DeepSpaceOneGameBundle:Ship:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $form = $this->createCreateForm();
        $form->handleRequest($request);

        if ($form->get('buy')->isClicked() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($form->getData());
            $em->flush();

            return $this->redirect($this->generateUrl('ships'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Ship entity.
     *
     * @Route("/{id}/edit", name="ships_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Ship $ship)
    {
        $deleteForm = $this->createDeleteForm($ship);
        $editForm = $this->createEditForm($ship);

        return array(
            'ship' => $ship,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ship entity.
     *
     * @Route("/{id}", name="ships_update")
     * @Method("PUT")
     * @Template("DeepSpaceOneGameBundle:Ship:edit.html.twig")
     */
    public function updateAction(Request $request, Ship $ship)
    {
        $deleteForm = $this->createDeleteForm($ship);
        $editForm = $this->createEditForm($ship);
        $editForm->handleRequest($request);

        if ($editForm->get('update')->isClicked() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('ships'));
        }

        return array(
            'ship' => $ship,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ship entity.
     *
     * @Route("/{id}", name="ships_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ship $ship)
    {
        $form = $this->createDeleteForm($ship);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ship);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ships'));
    }

    /**
     * Creates a form to create a Ship entity.
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createCreateForm()
    {
        $form = $this->createForm('deepspaceone_ship', null, array(
            'action' => $this->generateUrl('ships_create'),
            'method' => 'POST',
        ));

        $form->add('buy', 'submit', array('attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Creates a form to edit a Ship entity.
     *
     * @param Ship $ship The ship
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createEditForm(Ship $ship)
    {
        $form = $this->createForm('deepspaceone_ship', $ship, array(
            'action' => $this->generateUrl('ships_update', array('id' => $ship->getId())),
            'method' => 'PUT',
        ));

        $form->add('update', 'submit', array('attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Creates a form to delete a Ship entity by id.
     *
     * @param Ship $ship The ship
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Ship $ship)
    {
        return $this->createFormBuilder(null, array('style' => 'none'))
            ->setAction($this->generateUrl('ships_delete', array('id' => $ship->getId())))
            ->setMethod('DELETE')
            ->add('sell', 'submit', array('attr' => array('class' => 'btn-danger')))
            ->getForm()
        ;
    }
}
