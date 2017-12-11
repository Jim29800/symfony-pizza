<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\Command;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\Product;
use AppBundle\Entity\Recipe;
use AppBundle\Entity\Stock;


/**
 * @Route("/command")
 */
class CommandController extends Controller
{
    /**
     * @Route("/valid", name="command.valid")
     */
    public function commandAction(SessionInterface $session)
    {
        {
            $user = $this->getUser();
            $cart = $session->get('cart');
            $date = new \DateTime();
            foreach ($cart as $value) {
                //creer les commandes
                $command = new Command;
                $command
                    ->setUser($user)
                    ->setProduct($value)
                    ->setCreatedAt($date);
                $em = $this->getDoctrine()->getManager();
                $em->merge($command);

                //decompte des ingredients
                $list_recipe = $this->getDoctrine()->getRepository("AppBundle:Recipe")->findByProduct($value);
                $list_stock = $this->getDoctrine()->getRepository("AppBundle:Stock")->findAll();

                    foreach ($list_recipe as $value1) {
                        foreach ($list_stock as $key => $value2) {
                            if ($value1->getStock() == $value2) {
                                $list_stock[$key]->setAmount($list_stock[$key]->getAmount() - $value1->getAmount()); 
                                $em->persist($list_stock[$key]);
                            }
                        }
                    }

                $em->flush();
                $this->addFlash(
                    'note',
                    'La commande pour '. $value->getTitle().' est validée !'
                );


            }
            $session->remove("cart");

                return $this->redirectToRoute("cart.show");

        }
    }
    /**
     * @Route("/history", name="command.history")
     */
    public function historyAction(SessionInterface $session)
    {
        $user = $this->getUser();
        $command = $this->getDoctrine()->getRepository("AppBundle:Command")->findByUser($user);
        return $this->render(
            "command/commandHistory.html.twig",
            ["list" => $command]
        );
    }
}
    