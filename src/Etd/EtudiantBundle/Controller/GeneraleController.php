<?php

namespace Etd\EtudiantBundle\Controller;

namespace Etd\EtudiantBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class GeneraleController extends Controller
{
    public function indexAction()
    {
         $em = $this->getDoctrine()->getManager();
        $entitiesnbr = $em->getRepository('EtdEtudiantBundle:Personne')->findAll();
        $voitures = $em->getRepository('EtdEtudiantBundle:Voiture')->findAll();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->findBy(array(), array('nom' => 'ASC'), 5);
        usort($entities, array($this, 'cmp'));
        $val = count($entitiesnbr) / 5;
        if (count($entitiesnbr) % 5 > 0) {
            $val = (count($entitiesnbr) / 5) + 1;
        }
         return $this->render('EtdEtudiantBundle:Accueil:index.html.twig',array(
                    'entities' => $entities,
                    'nbr' => $val,
                    'total'=>count($entitiesnbr),
                    'voitures'=>count($voitures),
                    'listevoitures'=>$voitures,
        ));
       
    }
    private function cmp($a, $b) {
        return -strcmp($a->getDateajout(), $b->getDateajout());
    }
}
