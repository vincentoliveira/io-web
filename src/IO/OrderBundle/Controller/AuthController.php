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
use IO\OrderBundle\Form\ProfileType;

class AuthController extends BaseController
{

    /**
     * Storage Service
     * 
     * @Inject("io.storage_service")
     * @var \IO\OrderBundle\Service\StorageService
     */
    public $storage;

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
        $client = $this->storage->getClient();
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
        $client = $this->storage->getClient();
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
                    $this->storage->setClient($client);
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
        $client = $this->storage->getClient();
        if ($client !== null) {
            return $this->redirect($this->generateUrl('login_success'));
        } 
        
        $registerForm = $this->createForm(new RegisterType());
        
        if ($request->isMethod("POST")) {
            $registerForm->submit($request);
            if ($registerForm->isValid()) {
                $data = $registerForm->getData();
                $data['birthdate'] = $data['birthdate']->format('Y-m-d');
                $client = $this->apiClient->register($data);
                if ($client === null) {
                    $error = new FormError("Une erreur s'est produite.");
                    $registerForm->addError($error);
                } else {
                    $this->storage->setClient($client);
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
     * @Route("/profile", name="profile")
     * @Template()
     */
    public function profileAction(Request $request)
    {
        $client = $this->storage->getClient();
        $client['user']['identity']['birthdate'] = \DateTime::createFromFormat('Y-m-d H:i:s', $client['user']['identity']['birthdate']['date']);
        $client['user']['identity']['phones'] = [$client['user']['identity']['phone1'], $client['user']['identity']['phone2']];
        $client['user']['identity']['addresses'] = [$client['user']['identity']['address1']];
        
        $form = $this->createForm(new ProfileType(), $client['user']);
        if ($request->isMethod("POST")) {
            $form->submit($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $mergeData = array_merge($data, $data['identity']);
                $mergeData['birthdate'] = $mergeData['birthdate']->format('Y-m-d');
                $user = $this->apiClient->editUser($mergeData);
                if ($user) {
                    $client['user'] = $user;
                    $this->storage->setClient($client);
                    return $this->redirect($this->generateUrl('login_success'));
                }
            }
        }
        return array(
            'form' => $form->createView(),
        );
    }
    

    /**
     * @Route("/login_success", name="login_success")
     */
    public function loginSuccessAction()
    {
        $cart = $this->storage->getCart();
        $client = $this->storage->getClient();
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
        $this->storage->setClient(null);
        return $this->redirect($this->generateUrl('menu'));
    }
}
