<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomepageController extends DefaultController
{

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->redirect($this->generateUrl('menu'));
    }
}
