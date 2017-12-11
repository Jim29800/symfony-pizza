<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\Product;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/add/{id}", requirements={"id"="\d+"})
     */
    public function addAction(SessionInterface $session, Request $request, $id)
    {
        $ajout = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
        if (!$session->has('cart')) {
            $session->set('cart', [$ajout]);
        }else {
            $cart = $session->get('cart');
            array_push($cart, $ajout);
            $session->set('cart', $cart);
        }
        return $this->redirectToRoute("cart.show");
    }
    /**
     * @Route("/delete/{id}", requirements={"id"="\d+"})
     */
    public function deleteAction(SessionInterface $session, Request $request, $id)
    {
        $cart = $session->get('cart');
        unset($cart[$id]);
        $session->set('cart', $cart);
        return $this->redirectToRoute("cart.show");        
    }
    /**
     * @Route("/show", name="cart.show")
     */
    public function showAction(SessionInterface $session)
    {
        $cart = $session->get('cart');
        return $this->render(
        "cart/cart.html.twig",
        ["list" => $cart]
    );
    }
}

