<?php

namespace IO\DefaultBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IO\DefaultBundle\Utils\CommandeTraiteur;

class KidnapMamaController extends BaseController {

    /**
     * @Route("/traiteur", name="kidnap_mama")
     * @Template()
     */
    public function indexAction()
    {
        $ct = new CommandeTraiteur();
        $formBuilder = $this->createFormBuilder($ct);
        
        $formBuilder
                ->add('date',       'date')
                ->add('nom',        'text')
                ->add('prenom',     'text')
                ->add('adress',     'text')
                ->add('phone',      'text')
                ->add('number',     'text')
                ->add('place',      'text')
                ->add('placeadress','text')
                ->add('description','textarea');
        
        $form = $formBuilder->getForm();
        
        return $this->render('IODefaultBundle:KidnapMama:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
