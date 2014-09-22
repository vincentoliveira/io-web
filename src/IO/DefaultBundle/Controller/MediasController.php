<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MediasController extends DefaultController
{

    /**
     * @Route("/ilparlentdenous", name="medias")
     * @Template("IODefaultBundle:About:medias.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
