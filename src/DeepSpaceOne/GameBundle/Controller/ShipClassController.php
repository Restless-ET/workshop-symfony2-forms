<?php

namespace DeepSpaceOne\GameBundle\Controller;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotNull;

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
        $form = $this->createCreateForm();

        return array(
            // TASK 2: pass form to view
            'form' => $form->createView(),
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
        $form = $this->createCreateForm();
        $form->handleRequest($request);

        // TASK 2: update if condition
        if ($form->isValid()) {
            // TASK 2: create class
            $class = new ShipClass();
            $class->setName($form->get('name')->getData());
            $class->setCrewSize($form->get('crewSize')->getData());
            $class->setEquipmentCapacity($form->get('equipment')->getData());
            $class->setPayloadCapacity($form->get('payload')->getData());
            $class->setPrice($form->get('price')->getData());

            $em = $this->getDoctrine()->getManager();
            // TASK 2: persist class
            $em->persist($class);
            $em->flush();

            return $this->redirect($this->generateUrl('classes'));
        }

        return array(
            // TASK 2: pass form to view
            'form' => $form->createView(),
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
        $editForm = $this->createEditForm($class);

        return array(
            'class' => $class,
            'delete_form' => $deleteForm->createView(),
            // TASK 2: pass form to view
            'edit_form' => $editForm->createView(),
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
        $editForm = $this->createEditForm($class);
        $editForm->handleRequest($request);

        // TASK 2: update if condition
        if ($editForm->isValid()) {
            // TASK 2: update $class entity
            $class->setName($editForm->get('name')->getData());
            $class->setCrewSize($editForm->get('crewSize')->getData());
            $class->setEquipmentCapacity($editForm->get('equipment')->getData());
            $class->setPayloadCapacity($editForm->get('payload')->getData());
            $class->setPrice($editForm->get('price')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('classes'));
        }

        return array(
            'class' => $class,
            'delete_form' => $deleteForm->createView(),
            // TASK 2: pass form to view
            'edit_form' => $editForm->createView(),
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
     * Creates a preconfigured form builder for editing ShipClass entities.
     *
     * @return \Symfony\Component\Form\FormBuilderInterface The form builder
     */
    public function createShipClassFormBuilder()
    {
        return $this->createFormBuilder()
            ->add('name', null, array(
                'constraints' => array(
                    new NotNull(),
                    new Length(array('min' => 3, 'minMessage' => 'Please enter at least 3 characters.')),
                ),
            ))
            ->add('crewSize', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 0, 'minMessage' => 'The number of crew members cannot be negative.')),
                ),
            ))
            ->add('equipment', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 0, 'minMessage' => 'The number of mount points cannot be negative.')),
                ),
            ))
            ->add('payload', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 0, 'minMessage' => 'The payload capacity cannot be negative.')),
                ),
            ))
            ->add('price', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 1, 'minMessage' => 'The ship\'s price should be 1 or more.')),
                ),
            ))
        ;
    }

    /**
     * Creates a form for creating new ShipClass entities.
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createCreateForm()
    {
        return $this->createShipClassFormBuilder()
            ->setAction($this->generateUrl('classes_create'))
            ->add('create', 'submit')
            ->setData(array(
                'crewSize' => 2,
                'equipment' => 2,
                'payload' => 50,
                'price' => 100,
            ))
            ->getForm();
    }

    /**
     * Creates a form for editing existing ShipClass entities.
     *
     * @param ShipClass $class The ship class
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createEditForm(ShipClass $class)
    {
        return $this->createShipClassFormBuilder()
            ->setAction($this->generateUrl('classes_update', array('id' => $class->getId())))
            ->setMethod('PUT')
            ->add('update', 'submit')
            ->setData(array(
                'name' => $class->getName(),
                'crewSize' => $class->getCrewSize(),
                'equipment' => $class->getEquipmentCapacity(),
                'payload' => $class->getPayloadCapacity(),
                'price' => $class->getPrice(),
            ))
            ->getForm();
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
