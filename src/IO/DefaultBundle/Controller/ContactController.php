<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ContactController extends DefaultController
{

    /**
     * @Route("/contact", name="contact")
     * @Template("IODefaultBundle:Contact:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }
}
