<?php

namespace DeepSpaceOne\GameBundle\Controller;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\NotNull;

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
        $form = $this->generateCreateForm();

        return array(
            // TASK 1: pass form to view
            'form' => $form->createView(),
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
        $form = $this->generateCreateForm();

        $form->handleRequest($request);
        // TASK 1: update if condition
        if ($form->isValid()) {
            // TASK 1: create good
            $good = new Good();
            $good->setName($form->get('name')->getData());
            $good->setPricePerTon($form->get('price')->getData());

            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            // Validate unique register
            //$query = $em->createQuery('SELECT g FROM Good g WHERE g.name = :name');
            //$results = $query->execute(array('name' => $form->get('name')->getData()));
            $results = array();

            if (count($results) > 0) {
                $form->get('name')->addError(new FormError('A good with that name already exists.'));
            } else {
                // TASK 1: persist good
                $em->persist($good);
                $em->flush();

                $request->getSession()->getFlashBag()->add('form', 'The good was created successfully!');

                return $this->redirect($this->generateUrl('goods'));
            }
        }

        return array(
            // TASK 1: pass form to view
            'form' => $form->createView(),
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
        $editForm = $this->generateEditForm($good);

        return array(
            'good' => $good,
            'delete_form' => $deleteForm->createView(),
            // TASK 1: pass form to view
            'edit_form' => $editForm->createView(),
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
        $editForm = $this->generateEditForm($good);
        $editForm->handleRequest($request);

        // TASK 1: update if condition
        if ($editForm->isValid()) {
            // TASK 1: update $good entity
            $good->setName($editForm->get('name')->getData());
            $good->setPricePerTon($editForm->get('price')->getData());

            /** @var \Doctrine\ORM\EntityManager $em */
            $em = $this->getDoctrine()->getManager();
            // Validate unique register
            //$query = $em->createQuery('SELECT g FROM Good g WHERE g.name = :name');
            //$results = $query->execute(array('name' => $form->get('name')->getData()));
            $results = array();

            if (count($results) > 0) {
                $form->get('name')->addError(new FormError('A good with that name already exists.'));
            } else {
                $em->flush();

                $request->getSession()->getFlashBag()->add('form', 'The good was updated successfully!');

                return $this->redirect($this->generateUrl('goods'));
            }
        }

        return array(
            'good' => $good,
            'delete_form' => $deleteForm->createView(),
            // TASK 1: pass form to view
            'edit_form' => $editForm,
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

    private function generateCreateForm()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('goods_create'))
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Please insert a name.'
                    )),
                    new Length(array(
                        'min' => 2,
                        'minMessage' => 'Please enter a name with at least {{ limit }} characters.',
                    )),
                ),
             ))
            ->add('price', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 1)),
                )
             ))
            ->add('create', 'submit')
            ->getForm();

        return $form;
    }

    private function generateEditForm(Good $good)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('goods_update', array('id' => $good->getId())))
            ->setMethod('PUT')
            ->add('name', 'text', array(
                'constraints' => array(
                    new NotNull(array(
                        'message' => 'Please insert a name.'
                    )),
                    new Length(array(
                        'min' => 2,
                        'minMessage' => 'Please enter a name with at least {{ limit }} characters.',
                    )),
                ),
             ))
            ->add('price', 'integer', array(
                'constraints' => array(
                    new NotNull(),
                    new Range(array('min' => 1)),
                )
             ))
            ->add('update', 'submit')
            ->setData(array(
                'name' => $good->getName(),
                'price' => $good->getPricePerTon(),
            ))
            ->getForm();

        return $form;
    }
}
