<?php

namespace DeepSpaceOne\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use DeepSpaceOne\GameBundle\Entity\Good;

/**
 * Good controller.
 *
 * @Route("/goods")
 */
class GoodController extends Controller
{
    /**
     * Lists all Good entities.
     *
     * @Route("/", name="goods")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $goods = $em->getRepository('DeepSpaceOneGameBundle:Good')->findAll();

        return array(
            'goods' => $goods,
        );
    }

    /**
     * Displays a form to create a new Good entity.
     *
     * @Route("/new", name="goods_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        // TASK 1: create form

        return array(
            // TASK 1: pass form to view
        );
    }

    /**
     * Creates a new Good entity.
     *
     * @Route("/", name="goods_create")
     * @Method("POST")
     * @Template("DeepSpaceOneGameBundle:Good:new.html.twig")
     */
    public function createAction(Request $request)
    {
        // TASK 1: create form and handle the request

        // TASK 1: update if condition
        if (false) {
            // TASK 1: create good

            $em = $this->getDoctrine()->getManager();
            // TASK 1: persist good
            $em->flush();

            return $this->redirect($this->generateUrl('goods'));
        }

        return array(
            // TASK 1: pass form to view
        );
    }

    /**
     * Displays a form to edit an existing Good entity.
     *
     * @Route("/{id}/edit", name="goods_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction(Good $good)
    {
        $deleteForm = $this->createDeleteForm($good);

        // TASK 1: create edit form for $good

        return array(
            'good' => $good,
            'delete_form' => $deleteForm->createView(),
            // TASK 1: pass form to view
        );
    }

    /**
     * Edits an existing Good entity.
     *
     * @Route("/{id}", name="goods_update")
     * @Method("PUT")
     * @Template("DeepSpaceOneGameBundle:Good:edit.html.twig")
     */
    public function updateAction(Request $request, Good $good)
    {
        $deleteForm = $this->createDeleteForm($good);

        // TASK 1: create edit form and handle the request

        // TASK 1: update if condition
        if (false) {
            // TASK 1: update $good entity

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('goods'));
        }

        return array(
            'good' => $good,
            'delete_form' => $deleteForm->createView(),
            // TASK 1: pass form to view
        );
    }

    /**
     * Deletes a Good entity.
     *
     * @Route("/{id}", name="goods_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Good $good)
    {
        $form = $this->createDeleteForm($good);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($good);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('goods'));
    }

    /**
     * Creates a form to delete a Good entity.
     *
     * @param Good $good The good
     *
     * @return \Symfony\Component\Form\FormInterface The form
     */
    private function createDeleteForm(Good $good)
    {
        return $this->createFormBuilder(null, array('style' => 'none'))
            ->setAction($this->generateUrl('goods_delete', array('id' => $good->getId())))
            ->setMethod('DELETE')
            ->add('delete', 'submit', array('attr' => array('class' => 'btn-danger')))
            ->getForm()
        ;
    }
}
