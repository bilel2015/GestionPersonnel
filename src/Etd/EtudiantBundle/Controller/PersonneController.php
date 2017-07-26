<?php

namespace Etd\EtudiantBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Etd\EtudiantBundle\Entity\Personne;
use Etd\EtudiantBundle\Entity\Photopersonne;
use Etd\EtudiantBundle\Form\PersonneType;
use \DateTime;

/**
 * Personne controller.
 *
 */
class PersonneController extends Controller {

    /**
     * Lists all Personne entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $entitiesnbr = $em->getRepository('EtdEtudiantBundle:Personne')->findAll();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->findBy(array(), array('nom' => 'ASC'), 5);
        usort($entities, array($this, 'cmp'));
        $val = count($entitiesnbr) / 5;
        if (count($entitiesnbr) % 5 > 0) {
            $val = (count($entitiesnbr) / 5) + 1;
        }
        return $this->render('EtdEtudiantBundle:Personne:index.html.twig', array(
                    'entities' => $entities,
                    'nbr' => $val,
        ));
    }

    /**
     * Creates a new Personne entity.
     *
     */
    public function createAction(Request $request) {
        $entity = new Personne();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('personne_show', array('id' => $entity->getId())));
        }

        return $this->render('EtdEtudiantBundle:Personne:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    public function createajxAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $nom = $request->request->get('nom');
        $prenom = $request->request->get('prenom');
        $cne = $request->request->get('cne');
        $age = $request->request->get('age');
        $fonction = $request->request->get('fonction');
        $cne = trim($cne);
        $cne = preg_replace('/\s+/', ' ', $cne);
        $entitydouble = $em->getRepository('EtdEtudiantBundle:Personne')->getProByCne($cne);
        if ($entitydouble) {
            $response = new \Symfony\Component\HttpFoundation\Response();
            $output = array('success' => 'false');
            $response->headers->set('Content-Type', 'application/json');
            $response->setContent(json_encode($output));

            return $response;
        }
        $format = 'Y-m-d H:i:s';
        $dateajout = new DateTime();
        $dateajout->format($format);
        $entity = new Personne();
        $entity->setAge($age);
        $entity->setCne($cne);
        $entity->setFonction($fonction);
        $entity->setPrenom($nom);
        $entity->setNom($prenom);
        $entity->setDateajout($dateajout);
        $em->persist($entity);
        //$em->flush();
        ///
        $directory = __DIR__ . '/../../../../web/uploads';
        $data = $request->request->get('addPieceJoins');
        $url = $alt = $id = 'coco';

        $data = json_decode($data, true);

        if (!empty($data)) {
            // $data = json_decode($data, true);
            $arrayPieceJoine = [];
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]['idFile'] == '') {
                    $image = new Photopersonne();
                    $image->setAlt($data[$i]['nameFile']);
                    $image->setUrl($data[$i]['nameFile']);
                    $image->setPersonne($entity);
                    $em->persist($image);
                    $entity->addPhoto($image);
                    $em->flush();
                    $arrayPieceJoine[$i]['idPieceJoin'] = $image->getId();
                    copy($directory . '/temp/' . $data[$i]['nameFile'], $directory . '/pieceJoin/' . $image->getId() . '_' . $data[$i]['nameFile']);
                    unlink($directory . '/temp/' . $data[$i]['nameFile']);
                }
            }
        }
        ///
        $files = glob($directory . '/temp'); // get all file names
        foreach ($files as $file) { // iterate files
            if (is_file($file))
                unlink($file); // delete file
        }

        $response = new \Symfony\Component\HttpFoundation\Response();
        $output = array('success' => 'true','id' => $entity->getId(),'dateajout' => $entity->getDateajout());
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Creates a form to create a Personne entity.
     *
     * @param Personne $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Personne $entity) {
        $form = $this->createForm(new PersonneType(), $entity, array(
            'action' => $this->generateUrl('personne_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Personne entity.
     *
     */
    public function newAction() {
        $entity = new Personne();
        $form = $this->createCreateForm($entity);

        return $this->render('EtdEtudiantBundle:Personne:new.html.twig', array(
                    'entity' => $entity,
                    'form' => $form->createView(),
        ));
    }

    public function newajaxAction() {
         $em = $this->getDoctrine()->getManager();
        $entity = new Personne();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->findBy(array(), array('nom' => 'ASC'), 5);
        usort($entities, array($this, 'cmpdate'));
        return $this->render('EtdEtudiantBundle:Personne:newajax.html.twig', array('entities' => $entities,));
    }

    private function cmpdate($a, $b) {
        return -strcmp($a->getDateajout(), $b->getDateajout());
    }

    /**
     * Finds and displays a Personne entity.
     *
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Personne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        }
        $voitures = $entity->getVoitures();
        /* foreach ($entities as $personne) {
          $personnes[$i]['id'] = $personne->getId();
          } */
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Personne:show.html.twig', array(
                    'entity' => $entity,
                    'voitures' => $voitures,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Personne entity.
     *
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('EtdEtudiantBundle:Personne')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('EtdEtudiantBundle:Personne:edit.html.twig', array(
                    'entity' => $entity,
                    'edit_form' => $editForm->createView(),
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Personne entity.
     *
     * @param Personne $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Personne $entity) {
        $form = $this->createForm(new PersonneType(), $entity, array(
            'action' => $this->generateUrl('personne_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Personne entity.
     *
     */
    public function updateAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id = $request->get('id');
        $entity = $em->getRepository('EtdEtudiantBundle:Personne')->find($id);
        if (!$entity) {
            $output = array('modifier' => 'nonok');
            throw $this->createNotFoundException('Unable to find Personne entity.');
        } else {
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $age = $request->request->get('age');
            $cne = $request->request->get('cne');
            $fonction = $request->request->get('fonction');
            $entity->setAge($age);
            $entity->setCne($cne);
            $entity->setFonction($fonction);
            $entity->setPrenom($prenom);
            $entity->setNom($nom);
            $em->flush();
            $output = array('modifier' => 'ok');
        }

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Deletes a Personne entity.
     *
     */
    public function deleteAction(Request $request) {
        $directory = __DIR__ . '/../../../../web/uploads/';
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();
        $id = $request->request->get('id');
        $entity = $em->getRepository('EtdEtudiantBundle:Personne')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Personne entity.');
        } else {
            $photos = $entity->getPhotos();
            foreach ($photos as $photo) {
                unlink($directory . '/pieceJoin/' . $photo->getId() . '_' . $photo->getUrl());
            }

            $em->remove($entity);

            $em->flush();
            $output = array('delete' => 'ok');
        }

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($output));
        return $response;
    }

    /**
     * Creates a form to delete a Personne entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('personne_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

    /* developed by  Zaid ait Friha 
      load the list of persons by ajax */

    public function chargerAction() {
        $personnes = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->findAll();
        $i = 0;
        foreach ($entities as $personne) {
            $personnes[$i]['id'] = $personne->getId();
            $personnes[$i]['nom'] = $personne->getNom();
            $personnes[$i]['prenom'] = $personne->getPrenom();
            $personnes[$i]['age'] = $personne->getAge();
            $personnes[$i]['fonction'] = $personne->getFonction();
            $personnes[$i]['url'] = $personne->getPhotos()->first()->getUrl();
            $personnes[$i]['idphoto'] = $personne->getPhotos()->first()->getId();
            $i++;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($personnes));

        return $response;
    }

    private function cmp($a, $b) {
        return strcmp(strtolower($a->getNom()), strtolower($b->getNom()));
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
        $personnes = array();
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->parcinq($first);
        $i = 0;

        foreach ($entities as $personne) {
            $personnes[$i]['id'] = $personne->getId();
            $personnes[$i]['nom'] = $personne->getNom();
            $personnes[$i]['prenom'] = $personne->getPrenom();
            $personnes[$i]['age'] = $personne->getAge();
            $personnes[$i]['cne'] = $personne->getCne();
            $personnes[$i]['url'] = $personne->getPhotos()->first()->getUrl();
            $personnes[$i]['idphoto'] = $personne->getPhotos()->first()->getId();
            $personnes[$i]['fonction'] = $personne->getFonction();
            $i++;
        }
        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($personnes));
        return $response;
    }

    public function chercherAction() {
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $i = 0;
        if ($request->isXmlHttpRequest()) { // pour vérifier la présence d'une requete Ajax
            $chercher = '';
            $chercher = $request->request->get('chercher');
            $dataEntity = $em->getRepository('EtdEtudiantBundle:Personne')->Chercherpersonne($chercher);
            $nbr = count($dataEntity);
            usort($dataEntity, array($this, 'cmp'));
            return $this->render('EtdEtudiantBundle:Personne:chercherpersonne.html.twig', array('owner' => 'all', 'nombre' => $nbr, 'entities' => $dataEntity, 'valeurcherchee' => $chercher));
        }
    }

}
