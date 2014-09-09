<?php

namespace IO\OrderBundle\Twig;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\Tag;

/**
 * Order TwigExtension
 * 
 * @Service("io.order_twig_extension")
 * @Tag("twig.extension")
 */
class OrderTwigExtension extends \Twig_Extension
{

    /**
     * Container
     * 
     * @Inject("service_container")
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public $container;

    /**
     * Stockage Service
     * 
     * @Inject("io.stockage_service")
     * @var \IO\OrderBundle\Service\StockageService
     */
    public $stockage;

    /**
     * {@inheritDoc}
     */
    public function getGlobals()
    {
        return array(
            'cart' => $this->stockage->getCart(),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getFilters()
    {
        return array(
            'apiMedia' => new \Twig_SimpleFilter('apiMedia', array($this, 'apiMediaFilter')),
            'total_price' => new \Twig_SimpleFilter('total_price', array($this, 'totalPriceFilter')),
            'ordonate_products' => new \Twig_SimpleFilter('ordonate_products', array($this, 'ordonateProductsFilter')),
        );
    }

    /**
     * Return api media path
     * 
     * @return string
     */
    public function apiMediaFilter($mediaPath)
    {
        return $this->container->getParameter('io_api_base_url') . '/../media/' . $mediaPath;
    }

    /**
     * 
     * @param type $cart
     */
    public function totalPriceFilter($cart)
    {
        if ($cart === null) {
            return 0;
        }

        $total = 0;
        foreach ($cart['products'] as $product) {
            $total += $product['price'];
        }

        return $total;
    }

    /**
     * Ordonate product for cart
     * 
     * @param array $products
     * @return array
     */
    public function ordonateProductsFilter($products)
    {
        $result = array();

        foreach ($products as $product) {
            $id = $product['product_id'];
            $extra = $product['extra'];

            $key = $id . '-' . $extra;
            if (!isset($result[$key])) {
                $result[$key] = array(
                    'count' => 1,
                    'product' => $product,
                );
            } else {
                $result[$key]['count'] ++;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'order_twig_extension';
    }

}
