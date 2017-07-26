<?php

namespace Etd\EtudiantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Etd\EtudiantBundle\Entity\Photopersonne;
use Etd\EtudiantBundle\Form\PhotoType;

/**
 * Photo controller.
 *
 */
class PhotopersonneController extends Controller {

    /**
     * Lists all Photo entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('EtdEtudiantBundle:Photopersonne')->findAll();

        return $this->render('EtdEtudiantBundle:Photo:index.html.twig', array(
                    'entities' => $entities,
        ));
    }

    /**
     * Creates a new Photo entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Photopersonne();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('photo_show', array('id' => $entity->getId())));
        }

        return $this->render('EtdEtudiantBundle:Photo:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Photo entity.
     *
     * @param Photo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Photo $entity) {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Photo entity.
     *
     */
    public function newAction() {
        $entity = new Photopersonne();
        $form = $this->createCreateForm($entity);

        return $this->render('EtdEtudiantBundle:Photo:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Photo entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Photopersonne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Photo:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Photo entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Photopersonne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Photo:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Photo entity.
     *
     * @param Photo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Photopersonne $entity) {
        $form = $this->createForm(new PhotoType(), $entity, array(
            'action' => $this->generateUrl('photo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Photo entity.
     *
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Photopersonne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('photo_edit', array('id' => $id)));
        }

        return $this->render('EtdEtudiantBundle:Photo:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Photo entity.
     *
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('EtdEtudiantBundle:Photopersonne')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Photo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('photo'));
    }

    /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('photo_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    public function uploadAction(Request $request) {

        $uploadedFile = $request->files->get('file');


        $isUpload = false;
        if (!empty($uploadedFile)) {
//       echo "1 !empty";
//       var_dump($_FILES);
            $uploadedFile->move(__DIR__ . '/../../../../web/uploads/temp/', $uploadedFile->getClientOriginalName());
            $isUpload = true;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $output = array('rep' => $isUpload);
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /*by zaid ait friha 
     * Web Developer
     */
    public function supprimerAction(Request $request) {
        $uploadedFile = $request->request->get('file');
        $url=__DIR__ . '/../../../../web/uploads/temp/'.$uploadedFile;
        unlink($url);
         echo $url;die;
        $response = new \Symfony\Component\HttpFoundation\Response();
        $output = array('rep' => 'ok');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

}
