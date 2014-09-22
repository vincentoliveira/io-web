<?php

namespace IO\DefaultBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use IO\OrderBundle\Form\TraiteurType;

class TraiteurController extends DefaultController
{

    /**
     * @Route("/traiteur", name="traiteur")
     * @Template("IODefaultBundle:Contact:traiteur.html.twig")
     */
    public function indexAction()
    {
        $commande = $this->createForm(new TraiteurType());

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
     * @Route("/commandetraiteur", name="traiteur order")
     * @Template("IODefaultBundle:Contact:traiteur.html.twig")
     */
    public function traiteurOrderAction(Request $request) {
        $order = $this->createForm(new TraiteurType());

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

                $commande = $this->createForm(new TraiteurType());
                return array('form' => $commande->createView(),);
            }
        }

        return array('form' => $order->createView(),);
    }
}
