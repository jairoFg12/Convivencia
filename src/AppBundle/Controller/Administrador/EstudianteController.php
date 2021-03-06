<?php

namespace AppBundle\Controller\Administrador;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Estudiante;
use AppBundle\Form\EstudianteType;

/**
 * Estudiante controller.
 *
 * @Route("/administrador/estudiante")
 */
class EstudianteController extends Controller
{
    /**
     * Lists all Estudiante entities.
     *
     * @Route("/", name="estudiante_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $estudiantes = $em->getRepository('AppBundle:Estudiante')->findAll();

        return $this->render('administrador/estudiante/index.html.twig', array(
            'estudiantes' => $estudiantes,
        ));
    }

    /**
     * Creates a new Estudiante entity.
     *
     * @Route("/new", name="estudiante_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $estudiante = new Estudiante();
        $form = $this->createForm('AppBundle\Form\EstudianteType', $estudiante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estudiante);
            $em->flush();
            $this->MsgFlash("Creado correctamente.","info");

            return $this->redirectToRoute('estudiante_show', array('id' => $estudiante->getId()));
        }

        return $this->render('administrador/estudiante/new.html.twig', array(
            'estudiante' => $estudiante,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Estudiante entity.
     *
     * @Route("/{id}", name="estudiante_show")
     * @Method("GET")
     */
    public function showAction(Estudiante $estudiante)
    {
        $deleteForm = $this->createDeleteForm($estudiante);

        return $this->render('administrador/estudiante/show.html.twig', array(
            'estudiante' => $estudiante,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Estudiante entity.
     *
     * @Route("/{id}/edit", name="estudiante_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Estudiante $estudiante)
    {
        $deleteForm = $this->createDeleteForm($estudiante);
        $editForm = $this->createForm('AppBundle\Form\EstudianteType', $estudiante);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($estudiante);
            $em->flush();
            $this->MsgFlash("Actualización correcta.");

            return $this->redirectToRoute('estudiante_edit', array('id' => $estudiante->getId()));
        }

        return $this->render('administrador/estudiante/edit.html.twig', array(
            'estudiante' => $estudiante,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Estudiante entity.
     *
     * @Route("/{id}", name="estudiante_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Estudiante $estudiante)
    {
        $form = $this->createDeleteForm($estudiante);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($estudiante);
            $em->flush();
            $this->MsgFlash("Eliminado correctamente.","danger");
            
        }

        return $this->redirectToRoute('estudiante_index');
    }

    /**
     * Creates a form to delete a Estudiante entity.
     *
     * @param Estudiante $estudiante The Estudiante entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Estudiante $estudiante)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('estudiante_delete', array('id' => $estudiante->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    private function MsgFlash($mensaje = "Acción Realizada correctamente.", $tipoAlerta = 'success', $tituloAlerta = 'Mensaje: ')
    {
        $this->get('session')->getFlashBag()->add(
                'notice',
                array(
                    'alert' => $tipoAlerta,
                    'title' => $tituloAlerta,
                    'message' => $mensaje
                )
            );
    }
}
