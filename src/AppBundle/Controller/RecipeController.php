<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\Stock;
use AppBundle\Form\RecipeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @Route("/recipe")
 */
class RecipeController extends Controller
{
    /**
     * @Route("/edit/{id}", name="recipe.edit", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, $id)
    {
        {
            $list_stock = $this->getDoctrine()->getRepository("AppBundle:Stock")->findAll();
            $list = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findByProduct($id);;
            $product = $this->getDoctrine()->getRepository("AppBundle:Product")->find($id);
            $recipe = new Recipe();

            $recipe->setProduct($product);
            
            $form = $this->createForm(RecipeType::class, $recipe);
            $form
                ->add(
                'stock',
                ChoiceType::class,
                array(
                    "label" => "Ingrédient",
                    'choices' => $list_stock,
                    'choice_label' => 'title',
                    'choice_value' => 'id',
                    )
                )
                ->add("save", SubmitType::class, array('label' => "Enregistrer Ajouter l'ingredient"));

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($recipe);
                $em->flush();
                return $this->redirectToRoute("recipe.edit", array('id' => $id));
            }

            return $this->render("recipe/recipeEdit.html.twig", [
                "list" => $list,
                "form" => $form->createView(),
                "idProduit" => $id,
            ]);
        }
    }
    /**
     * @Route("/delete/{idProduit}/{id}", name="recipe.delette", requirements={"id"="\d+"})
     */
    public function deleteAction(Request $request, $idProduit, $id)
    {
        $product = $this->getDoctrine()->getRepository("AppBundle:Recipe")->find($id);
        $em = $this->getDoctrine()->getManager();        
        $em->remove($product);
        $em->flush();
        return $this->redirectToRoute("recipe.edit", array('id' => $idProduit));
        
    }
}
