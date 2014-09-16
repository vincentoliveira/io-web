<?php

namespace IO\OrderBundle\Controller;

use IO\DefaultBundle\Controller\DefaultController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\DiExtraBundle\Annotation\Inject;

class OrderController extends BaseController
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
     * @Route("/carte", name="menu")
     * @Template()
     */
    public function menuAction(Request $request)
    {
        $menu = $this->getMenu($request->query->has('reset'));
        
        // not validated
        $cart = $this->storage->getCart();
        if ($cart && isset($cart['validated']) && $cart['validated']) {
            $cart['validated'] = false;
            $this->storage->setCart($cart);
        }

        return array(
            'menu' => $menu,
        );
    }

    /**
     * @Route("/product/add", name="add_product")
     * @Template("IOOrderBundle:Order:menu.html.twig")
     */
    public function addProductAction(Request $request)
    {
        $menu = $this->getMenu($request->query->has('reset'));
        $cart = $this->storage->getCart();
        $productId = $request->request->get('product_id');
        if ($productId) {
            $options = $request->request->get('options');

            $newCart = $this->apiClient->addProduct($cart, $productId, $options);
            if ($newCart) {
                $newCart['validated'] = false;
                $this->storage->setCart($newCart);
            }
        }
        return array(
            'menu' => $menu,
        );
    }

    /**
     * @Route("/product/remove", name="remove_product")
     * @Template("IOOrderBundle:Order:menu.html.twig")
     */
    public function removeProductAction(Request $request)
    {
        $menu = $this->getMenu($request->query->has('reset'));
        $cart = $this->storage->getCart();
        $productId = $request->request->get('product_id');
        if ($productId && $cart) {
            $extra = $request->request->get('extra');

            $newCart = $this->apiClient->removeProduct($cart, $productId, $extra);
            if ($newCart) {
                $newCart['validated'] = false;
                $this->storage->setCart($newCart);
            }
        }

        return array(
            'menu' => $menu,
        );
    }

    /**
     * @Route("/panier", name="order_recap")
     * @Template()
     */
    public function recapAction(Request $request)
    {
        $cart = $this->storage->getCart();
        if ($cart === null || empty($cart['products'])) {
            $this->redirect($this->generateUrl('menu'));
        }
        
        if ($request->isMethod('POST')) {
            $orderType = $request->request->get('order_type');
            $this->storage->set('order_type', $orderType);
            $deliveryTime = $request->request->get('order_delivery_date');
            $deliveryDate = \DateTime::createFromFormat('H:i', $deliveryTime);
            $this->storage->set('client_delivery_date', $deliveryDate);
            $orderPostcode = intval($request->request->get('order_postcode'));
            $this->storage->set('order_postcode', $orderPostcode);
        }
        return array();
    }
    
    /**
     * @Route("/panier/confirm", name="order_valid_recap")
     * @Method("POST")
     */
    public function validRecapAction(Request $request)
    {
        $cart = $this->storage->getCart();
        $orderType = $this->storage->get('order_type');
        if ($cart === null || empty($cart['products']) || $orderType === null) {
            $this->redirect($this->generateUrl('menu'));
        }
        
        $cart['validated'] = true;
        $this->storage->setCart($cart);
        
        return $this->redirect($this->generateUrl('auth'));
    }

    /**
     * Get menu
     * 
     * @return array
     */
    protected function getMenu($reset = false)
    {
        $menu = $this->storage->getMenu(true);
        if (!is_array($menu) || !isset($menu['products']) || $reset) {
            $menu = $this->apiClient->loadMenu();
            $this->storage->setMenu($menu);
        }

        return $menu;
    }

}
