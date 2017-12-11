<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recipe;

use AppBundle\Form\ProductType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/list", name="product.list")
     */
    public function listAction(Request $request)
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:Product")->findAll();



        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->add("save", SubmitType::class, array('label' => "Créer le plat"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute("product.list");
        }

        return $this->render("product/productList.html.twig", [
            "list" => $list,
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/edit/{id}", name="product.edit", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id)
    {
        {

            $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
            $form = $this->createForm(ProductType::class, $product);
            $form->add("save", SubmitType::class, array('label' => "Enregistrer Ajouter l'ingredient"));

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($product);
                $em->flush();
                return $this->redirectToRoute("product.list");
            }

            return $this->render("product/productEdit.html.twig", [
                "form" => $form->createView(),
            ]);
        }
    }
    /**
     * @Route("/card", name="card")
     */
    public function cardAction()
    {
        $list_product = $this->getDoctrine()->getRepository("AppBundle:Product")->findAll();
        $list_recipe = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findAll();
        
        //verifie la disponibilité via les ingredients
        foreach ($list_recipe as $value) {
            if ($value->getStock()->getAmount() < $value->getAmount()) {
                    foreach ($list_product as $key => $value2) {
                        if ($value->getProduct() == $value2) {
                            unset($list_product[$key]);
                        }
                    }
            }
        }

        return $this->render("product/productCard.html.twig", [
            "list" => $list_product,
        ]);
    }
}
