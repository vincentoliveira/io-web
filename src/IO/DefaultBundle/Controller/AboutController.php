<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AboutController extends DefaultController
{

    /**
     * @Route("/about", name="about")
     * @Template("IODefaultBundle:About:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
