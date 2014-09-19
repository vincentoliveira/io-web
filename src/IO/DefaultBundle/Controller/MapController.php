<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MapController extends DefaultController
{

    /**
     * @Route("/ounoustrouver", name="map")
     * @Template("IODefaultBundle:Contact:map.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
