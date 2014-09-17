<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class HomepageController extends DefaultController
{

    /**
     * @Route("/", name="homepage")
     * @Template("IODefaultBundle:Homepage:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
