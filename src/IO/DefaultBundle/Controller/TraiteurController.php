<?php

namespace IO\DefaultBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use IO\OrderBundle\Form\KidnapOrderType;

class TraiteurController extends BaseController {

    /**
     * @Route("/traiteur", name="traiteur")
     * @Template()
     */
    public function traiteurAction() {
        $commande = $this->createForm(new KidnapOrderType());

        return array('form' => $commande->createView(),);
    }

    private function sendValidationMail($data) {
        $message = \Swift_Message::newInstance()
                ->setSubject('Commande traiteur envoyée')
                ->setFrom('louis@innovorder.fr')
                ->setTo($data['email'])
                ->setBody($this->renderView('IOOrderBundle:Mail:ValidationEmail.txt.twig',
                        array(
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'email' => $data['email'],
                    'date' => $data['date'],
                    'number' => $data['number'],
                    'adress' => $data['adress'],
                    'description' => $data['description']
                )));
        $this->get('mailer')->send($message);
    }

    private function sendOrderMail($data) {
        $message = \Swift_Message::newInstance()
                ->setSubject('Nouvelle commande traiteur')
                ->setFrom($data['email'])
                ->setTo('louis@innovorder.fr')
                ->setBody($this->renderView('IOOrderBundle:Mail:OrderEmail.txt.twig',
                        array(
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'email' => $data['email'],
                    'date' => $data['date'],
                    'number' => $data['number'],
                    'adress' => $data['adress'],
                    'description' => $data['description']
                )));
        $this->get('mailer')->send($message);
    }

    /**
     * @Route("/kidnap", name="kidnap")
     * @Template("IODefaultBundle:KidnapMama:index.html.twig")
     */
    public function kidnapAction(Request $request) {
        $order = $this->createForm(new KidnapOrderType());

        if ($request->isMethod("POST")) {
            $order->submit($request);
            if ($order->isValid()) {
                $data = $order->getData();
                $data['date'] = $data['date']->format('d/m/Y');
                $data2 = array(
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'email' => $data['email'],
                    'date' => $data['date'],
                    'number' => $data['number'],
                    'adress' => $data['adress'],
                    'description' => $data['description'],
                );
                $this->sendValidationMail($data2);
                $this->sendOrderMail($data2);

                $commande = $this->createForm(new KidnapOrderType());
                return array('form' => $commande->createView(),);
            }
        }

        return array('form' => $order->createView(),);
    }
}
