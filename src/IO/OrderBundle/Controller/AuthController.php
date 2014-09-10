<?php

namespace IO\OrderBundle\Controller;

use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation\Inject;
use IO\OrderBundle\Form\LoginType;
use IO\OrderBundle\Form\RegisterType;

class AuthController extends BaseController
{

    /**
     * Stockage Service
     * 
     * @Inject("io.storage_service")
     * @var \IO\OrderBundle\Service\StorageService
     */
    public $stockage;

    /**
     * ApiClient Service
     * 
     * @Inject("io.api_client_service")
     * @var \IO\OrderBundle\Service\ApiClientService
     */
    public $apiClient;

    /**
     * @Route("/auth", name="auth")
     * @Template()
     */
    public function authAction()
    {
        $client = $this->stockage->getClient();
        if ($client !== null) {
            return $this->redirect($this->generateUrl('login_success'));
        } 
        
        $loginForm = $this->createForm(new LoginType());
        $registerForm = $this->createForm(new RegisterType());

        return array(
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        );
    }

    /**
     * @Route("/login", name="login")
     * @Template("IOOrderBundle:Auth:auth.html.twig")
     */
    public function loginAction(Request $request)
    {
        $client = $this->stockage->getClient();
        if ($client !== null) {
            return $this->redirect($this->generateUrl('login_success'));
        } 
        
        $loginForm = $this->createForm(new LoginType());

        if ($request->isMethod("POST")) {
            $loginForm->submit($request);
            if ($loginForm->isValid()) {
                $client = $this->apiClient->authenticate($loginForm->getData());
                if ($client === null) {
                    $error = new FormError("La combinaison email/mot de passe est incorrecte.");
                    $loginForm->addError($error);
                } else {
                    $this->stockage->setClient($client);
                    return $this->redirect($this->generateUrl('login_success'));
                }
            }
        }

        $registerForm = $this->createForm(new RegisterType());
        return array(
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        );
    }
    
    /**
     * @Route("/register", name="register")
     * @Template("IOOrderBundle:Auth:auth.html.twig")
     */
    public function registerAction(Request $request)
    {
        $client = $this->stockage->getClient();
        if ($client !== null) {
            return $this->redirect($this->generateUrl('login_success'));
        } 
        
        $registerForm = $this->createForm(new RegisterType());
        
        if ($request->isMethod("POST")) {
            $registerForm->submit($request);
            if ($registerForm->isValid()) {
                $data = $registerForm->getData();
                $data['birthdate'] = $data['birthdate']->format('d/m/Y');
                $client = $this->apiClient->register($data);
                if ($client === null) {
                    $error = new FormError("Une erreur s'est produite.");
                    $registerForm->addError($error);
                } else {
                    $this->stockage->setClient($client);
                    return $this->redirect($this->generateUrl('login_success'));
                }
            }
        }

        $loginForm = $this->createForm(new LoginType());
        return array(
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        );
    }
    

    /**
     * @Route("/login_success", name="login_success")
     */
    public function loginSuccessAction()
    {
        $cart = $this->stockage->getCart();
        $client = $this->stockage->getClient();
        if ($client !== null && $cart !== null && $cart['validated']) {
            return $this->redirect($this->generateUrl('payment_index'));
        } else {
            return $this->redirect($this->generateUrl('menu'));
        }
    }


    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        $this->stockage->setClient(null);
        return $this->redirect($this->generateUrl('menu'));
    }
}
