<?php

namespace Etd\EtudiantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Etd\EtudiantBundle\Entity\Voiture;
use Etd\EtudiantBundle\Entity\Photovoiture;
use Etd\EtudiantBundle\Form\VoitureType;
use \DateTime;
/**
 * Voiture controller.
 *
 */
class VoitureController extends Controller {

    /**
     * Lists all Voiture entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entitiesnbr = $em->getRepository('EtdEtudiantBundle:Voiture')->findAll();
        $entities = $em->getRepository('EtdEtudiantBundle:Voiture')->findAll();
        $val = count($entitiesnbr) / 5;
        if (count($entitiesnbr) % 5 > 0) {
            $val = (count($entitiesnbr) / 5) + 1;
        }
        return $this->render('EtdEtudiantBundle:Voiture:index.html.twig', array(
                    'entities' => $entities,
                    'nbr' => $val,
        ));
    }

    /**
     * Creates a new Voiture entity.
     *
     */
    public function createAction(Request $request) {
       
         $em = $this->getDoctrine()->getManager();
        $entity = new Voiture();
        $request = $this->getRequest();
        $marque = $request->request->get('marque');
        $model = $request->request->get('model');
        $matricule = $request->request->get('matricule');
        $proprietaire = $request->request->get('proprietaire');
        $personne = $em->getRepository('EtdEtudiantBundle:Personne')->find($proprietaire);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voiture entity.');
        }
        $entity = new Voiture();
        $format = 'Y-m-d H:i:s';
        $dateajout = new DateTime();
        $dateajout->format($format);
        $entity->setMarque($marque);
        $entity->setDateajout($dateajout);
        $entity->setMatricule($matricule);
        $entity->setModel($model);
        $entity->setPersonne($personne);
        $em->persist($entity);
        $directory = __DIR__ . '/../../../../web/uploads';
        $data = $request->request->get('addPieceJoins');
        $url = $alt = $id = 'coco';

        $data = json_decode($data, true);
       
        if (!empty($data)) {
            // $data = json_decode($data, true);
            $arrayPieceJoine = [];
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['idFile'] == '') {
                    $image = new Photovoiture();
                    $image->setAlt($data[$i]['nameFile']);
                    $image->setUrl($data[$i]['nameFile']);
                    $image->setMavoiture($entity);
                    $em->persist($image);
                    $entity->addVoituresphoto($image);
                    $em->flush();
                    $arrayPieceJoine[$i]['idPieceJoin'] = $image->getId();
                    copy($directory . '/temp/' . $data[$i]['nameFile'], $directory . '/pieceJoin/' . $image->getId() . '_' . $data[$i]['nameFile']);
                    unlink($directory . '/temp/' . $data[$i]['nameFile']);
                }
            }
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $output = array('voiture' => $entity->getId(), 'resultat' => 'ok');
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Creates a form to create a Voiture entity.
     *
     * @param Voiture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Voiture $entity) {
        $form = $this->createForm(new VoitureType(), $entity, array(
            'action' => $this->generateUrl('voiture_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Voiture entity.
     *
     */
    public function newAction() {
        $entity = new Voiture();
        $form = $this->createCreateForm($entity);

        return $this->render('EtdEtudiantBundle:Voiture:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    public function newajaxAction() {
        $entity = new Voiture();
        return $this->render('EtdEtudiantBundle:Voiture:newajax.html.twig');
    }

    /**
     * Finds and displays a Voiture entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Voiture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voiture entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Voiture:show.html.twig', array(
                    'entity' => $entity,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Voiture entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Voiture')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Voiture entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Voiture:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Voiture entity.
     *
     * @param Voiture $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Voiture $entity) {
        $form = $this->createForm(new VoitureType(), $entity, array(
            'action' => $this->generateUrl('voiture_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Voiture entity.
     *
     */
    public function updateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id = $request->get('id');
        $proprietaire = $request->request->get('proprietaire');
        $entity = $em->getRepository('EtdEtudiantBundle:Voiture')->find($id);
        $personne = $em->getRepository('EtdEtudiantBundle:Personne')->find($proprietaire);
        if (!$entity) {
            $output = array('modifier' => 'nonok');
            throw $this->createNotFoundException('Unable to find Personne entity.');
        } else {
            $marque = $request->request->get('marque');
            $model = $request->request->get('model');
            $matricule = $request->request->get('matricule');

            $entity->setMarque($marque);
            $entity->setModel($model);
            $entity->setMatricule($matricule);
            $entity->setPersonne($personne);
            $em->flush();
            $output = array('modifier' => 'ok');
        }

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Deletes a Voiture entity.
     *
     */
    public function deleteAction(Request $request) {
        $directory = __DIR__ . '/../../../../web/uploads/';
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id = $request->request->get('id');

        $entity = $em->getRepository('EtdEtudiantBundle:Voiture')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        } else {
            $photos= $entity->getVoituresphotos();
            foreach ($photos as $photo) {
            unlink($directory.'/pieceJoin/'.$photo->getId().'_'.$photo->getUrl());
        }
            $em->remove($entity);
            $em->flush();
            echo $entity->getId();
            die;
            $output = array('delete' => 'ok');
        }

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Creates a form to delete a Voiture entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('voiture_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    public function paginationAction(Request $request) {
        $request = $this->getRequest();
        $first = $request->request->get('first');
        $page = $request->request->get('page');
        if ($page > 1) {
            $first = (($page * 5) - 5);
        } else {
            $first = 0;
        }
        $voitures = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('EtdEtudiantBundle:Voiture')->parcinq($first);
        $i = 0;

        foreach ($entities as $voiture) {
            $voitures[$i]['id'] = $voiture->getId();
            $voitures[$i]['marque'] = $voiture->getMarque();
            $voitures[$i]['model'] = $voiture->getModel();
            $voitures[$i]['matricule'] = $voiture->getMatricule();
            $voitures[$i]['proprietaire'] = $voiture->getPersonne()->getNom();
            $i++;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($voitures));
        return $response;
    }
    public function chargerAction() {
        $voituresall = array();
        $em = $this->getDoctrine()->getManager();
        $voitures = $em->getRepository('EtdEtudiantBundle:Voiture')->findAll();
        $i = 0;
        foreach ($voitures as $voiture) {
            $voituresall[$i]['id'] = $voiture->getId();
            /*$personnes[$i]['nom'] = $personne->getNom();
            $personnes[$i]['prenom'] = $personne->getPrenom();
            $personnes[$i]['age'] = $personne->getAge();
            $personnes[$i]['fonction'] = $personne->getFonction();*/
            $voituresall[$i]['url'] = $voiture->getVoituresphotos()->first()->getUrl();
            $voituresall[$i]['idphoto'] = $voiture->getVoituresphotos()->first()->getId();
            $i++;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($voituresall));

        return $response;
    }
}
