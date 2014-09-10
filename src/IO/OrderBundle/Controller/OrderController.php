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
     * @Route("/carte", name="menu")
     * @Template()
     */
    public function menuAction(Request $request)
    {
        $menu = $this->getMenu($request->query->has('reset'));

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
        $cart = $this->stockage->getCart();
        $productId = $request->request->get('product_id');
        if ($productId) {
            $options = $request->request->get('options');

            $newCart = $this->apiClient->addProduct($cart, $productId, $options);
            if ($newCart) {
                $this->stockage->setCart($newCart);
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
        $cart = $this->stockage->getCart();
        $productId = $request->request->get('product_id');
        if ($productId && $cart) {
            $extra = $request->request->get('extra');

            $newCart = $this->apiClient->removeProduct($cart, $productId, $extra);
            if ($newCart) {
                $this->stockage->setCart($newCart);
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
        $cart = $this->stockage->getCart();
        if ($cart === null || empty($cart['products'])) {
            $this->redirect($this->generateUrl('menu'));
        }
        
        if ($request->isMethod('POST')) {
            $orderType = $request->request->get('order_type');
            $this->stockage->set('order_type', $orderType);
            $orderPostcode = intval($request->request->get('order_postcode'));
            $this->stockage->set('order_postcode', $orderPostcode);
        }
        return array();
    }
    

    /**
     * @Route("/panier/confirm", name="order_valid_recap")
     * @Method("POST")
     */
    public function validRecapAction(Request $request)
    {
        $orderType = $request->request->get('order_type');
        $orderPostcode = intval($request->request->get('order_postcode'));
        $this->stockage->set('order_type', $orderType);
        $this->stockage->set('order_postcode', $orderPostcode);        
        
        return $this->redirect($this->generateUrl('auth'));
    }

    /**
     * Get menu
     * 
     * @return array
     */
    protected function getMenu($reset = false)
    {
        $menu = $this->stockage->getMenu();
        if (!is_array($menu) || $reset) {
            $menu = $this->apiClient->loadMenu();
            $this->stockage->setMenu($menu);
        }

        return $menu;
    }

}
