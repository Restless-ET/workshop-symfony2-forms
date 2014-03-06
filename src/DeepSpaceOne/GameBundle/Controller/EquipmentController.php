<?php

namespace DeepSpaceOne\GameBundle\Controller;

use DeepSpaceOne\GameBundle\Entity\Equipment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;

/**
 * Handles the equipment CRUD operations.
 *
 * @Route("/equipment")
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class EquipmentController extends Controller
{
    /**
     * Lists all Equipment entities.
     *
     * @Route("/", name="equipment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $equipments = $em->getRepository('DeepSpaceOneGameBundle:Equipment')->findAll();

        return array(
            'equipments' => $equipments,
        );
    }

    /**
     * Displays a form to create a new Equipment entity.
     *
     * @Route("/new", name="equipment_new")
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
     * Creates a new Equipment entity.
     *
     * @Route("/", name="equipment_create")
     * @Method("POST")
     * @Template("DeepSpaceOneGameBundle:Equipment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $form = $this->createCreateForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $equipment = new Equipment();
            $equipment->setName($form->get('name')->getData());
            $equipment->setPrice($form->get('price')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->redirect($this->generateUrl('equipment'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Equipment entity.
     *
     * @Route("/{id}/edit", name="equipment_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Equipment $equipment)
    {
        $deleteForm = $this->createDeleteForm($equipment);
        $editForm = $this->createEditForm($equipment);

        return array(
            'equipment' => $equipment,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Edits an existing Equipment entity.
     *
     * @Route("/{id}", name="equipment_update")
     * @Method("PUT")
     * @Template("DeepSpaceOneGameBundle:Equipment:edit.html.twig")
     */
    public function updateAction(Request $request, Equipment $equipment)
    {
        $deleteForm = $this->createDeleteForm($equipment);
        $editForm = $this->createEditForm($equipment);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $equipment->setName($editForm->get('name')->getData());
            $equipment->setPrice($editForm->get('price')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('equipment'));
        }

        return array(
            'equipment' => $equipment,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Deletes a Equipment entity.
     *
     * @Route("/{id}", name="equipment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Equipment $equipment)
    {
        $form = $this->createDeleteForm($equipment);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('equipment'));
    }

    /**
     * Creates a preconfigured form builder for editing Equipment entities.
     *
     * @return \Symfony\Component\Form\FormBuilderInterface The form builder
     */
    private function createEquipmentFormBuilder()
    {
        return $this->createFormBuilder()
           ->add('name', null, array(
                'constraints' => array(
                    new NotNull(),
                    new Length(array('min' => 3)),
                ),
            ))
           ->add('price', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 1)),
                ),
            ))
        ;
    }

    /**
     * Creates a form to create an Equipment entity.
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createCreateForm()
    {
        return $this->createEquipmentFormBuilder()
           ->add('submit', 'submit', array(
                'label' => 'Create',
            ))
           ->setData(array('price' => 10))
           ->setAction($this->generateUrl('equipment_create'))
           ->getForm();
    }

    /**
     * Creates a form to edit an Equipment entity.
     *
     * @param Equipment $equipment The equipment to edit.
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createEditForm(Equipment $equipment)
    {
        return $this->createEquipmentFormBuilder()
           ->add('submit', 'submit', array(
                'label' => 'Update',
            ))
           ->setData(array('name' => $equipment->getName(), 'price' => $equipment->getPrice()))
           ->setAction($this->generateUrl('equipment_update', array('id' => $equipment->getId())))
           ->setMethod('PUT')
           ->getForm();
    }

    /**
     * Creates a form to delete an Equipment entity.
     *
     * @param Equipment $equipment The equipment
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Equipment $equipment)
    {
        return $this->createFormBuilder(null, array('style' => 'none'))
            ->setMethod('DELETE')
            ->setAction($this->generateUrl('equipment_delete', array('id' => $equipment->getId())))
            ->add('delete', 'submit', array('attr' => array('class' => 'btn-danger')))
            ->getForm()
        ;
    }
}
