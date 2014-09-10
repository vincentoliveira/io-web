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
     * @Inject("io.stockage_service")
     * @var \IO\OrderBundle\Service\StockageService
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
        $loginForm = $this->createForm(new LoginType());

        if ($request->isMethod("POST")) {
            $loginForm->submit($request);
            if ($loginForm->isValid()) {
                $token = $this->apiClient->authenticate($loginForm->getData());
                if ($token === null) {
                    $error = new FormError("La combinaison email/mot de passe est incorrecte.");
                    $loginForm->addError($error);
                } else {
                    echo '<pre>';
                    print_r($token);
                    die;
                }
            }
        }

        $registerForm = $this->createForm(new RegisterType());
        return array(
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        );
    }

}
