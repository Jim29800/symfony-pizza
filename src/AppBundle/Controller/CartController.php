<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\Stock;


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
        //suppression des ingredients
        $list_recipe = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findByProduct($ajout);
        $list_stock = $this->getDoctrine()->getRepository("AppBundle:Stock")->findAll();
        $em = $this->getDoctrine()->getManager();
        foreach ($list_recipe as $value1) {
            foreach ($list_stock as $key => $value2) {
                if ($value1->getStock() == $value2) {
                    $list_stock[$key]->setAmount($list_stock[$key]->getAmount() - $value1->getAmount());
                    $em->persist($list_stock[$key]);
                }
            }
        }
        $em->flush();



        return $this->redirectToRoute("cart.show");
    }
    /**
     * @Route("/delete/{id}", requirements={"id"="\d+"})
     */
    public function deleteAction(SessionInterface $session, Request $request, $id)
    {
        $cart = $session->get('cart');

        //rajout des ingredients
        $em = $this->getDoctrine()->getManager();        
        $product = $cart[$id];        
        $list_recipe = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findByProduct($product);
        $list_stock = $this->getDoctrine()->getRepository("AppBundle:Stock")->findAll();

        foreach ($list_recipe as $value1) {
            foreach ($list_stock as $key => $value2) {
                if ($value1->getStock() == $value2) {
                    $list_stock[$key]->setAmount($list_stock[$key]->getAmount() + $value1->getAmount());
                    $em->persist($list_stock[$key]);
                }
            }
        }
        $em->flush();        
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

