<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Stock;
use AppBundle\Form\StockType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/stock")
 */
class StockController extends Controller
{
    /**
     * @Route("/list", name="stock.list")
     */
    public function listAction(Request $request)
    {
        $list = $this->getDoctrine()->getRepository("AppBundle:Stock")->findAll();



        $stock = new Stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->add("save", SubmitType::class, array('label' => "Enregistrer l'ingredient"));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($stock);
            $em->flush();
            return $this->redirectToRoute("stock.list");
        }

        return $this->render("stockList.html.twig", [
            "list" => $list,
            "form" => $form->createView(),
            ]);
    }
    /**
     * @Route("/edit/{id}", name="stock.edit", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id)
    {
        {

            $stock = $this->getDoctrine()->getRepository("AppBundle:Stock")->find($id);
            $form = $this->createForm(StockType::class, $stock);
            $form->add("save", SubmitType::class, array('label' => "Enregistrer l'ingredient"));

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($stock);
                $em->flush();
                return $this->redirectToRoute("stock.list");
            }

            return $this->render("stockEdit.html.twig", [
                "form" => $form->createView(),
            ]);
        }
    }    
}
