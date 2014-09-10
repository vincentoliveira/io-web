<?php

namespace IO\DefaultBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IO\DefaultBundle\Utils\GoogleMapAPI;


class TrouverMamaController extends BaseController {

    /**
     * @Route("/contact", name="trouver_mama")
     * @Template()
     */
    public function indexAction()
    {
        $map = new GoogleMapAPI('map');
        $map->setAPIKey('AIzaSyAv4BIv4XIqzUNvG-Pcnsvn282QuyxefuM');
        
        $map->setWidth('800px');
        $map->setHeight('500px');
        $map->setCenterCoords('2.352469', '48.867142');
        $map->setZoomLevel(5);
        $map->setControlSize('small');
        
        //$list = array($map);
        return $this->render('IODefaultBundle:TrouverMama:index.html.twig', array('googlemap' => $map->printMap()));
        //return array();
    }

}
