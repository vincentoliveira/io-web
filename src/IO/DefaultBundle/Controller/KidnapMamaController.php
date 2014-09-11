<?php

namespace IO\DefaultBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IO\OrderBundle\Form\KidnapOrderType;

class KidnapMamaController extends BaseController {

    /**
     * @Route("/traiteur", name="kidnap_mama")
     * @Template()
     */
    public function indexAction()
    {
        $commande = $this->createForm(new KidnapOrderType());
        
        return array('form' => $commande->createView(),);
    }
    
    /**
     * @Route("/kidnap", name="kidnap")
     * @Template("IODefaultBundle:KidnapMama:index.html.twig")
     */
    public function kidnapAction(Request $request)
    {
        $order = $this->createForm(new KidnapOrderType());
        
        if ($request->isMethod("POST")) {
            $order->submit($request);
            if ($order->isValid()) {
                $data = $order->getData();
                $data['date'] = $data['date']->format('d/m/Y');//*/
                return $this->redirect($this->generateUrl('sendmail'));
            }
        }

        return array('form' => $order->createView(),);
    }

}
