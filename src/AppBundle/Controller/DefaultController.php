<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
namespace Etd\EtudiantBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entitiesnbr = $em->getRepository('EtdEtudiantBundle:Personne')->findAll();
        $entities = $em->getRepository('EtdEtudiantBundle:Personne')->findBy(array(), array('nom' => 'ASC'), 5);
        usort($entities, array($this, 'cmp'));
        $val = count($entitiesnbr) / 5;
        if (count($entitiesnbr) % 5 > 0) {
            $val = (count($entitiesnbr) / 5) + 1;
        }
        return $this->render('default/index.html.twig',array(
                    'entities' => $entities,
                    'nbr' => $val,
        ));
       
    }
}
