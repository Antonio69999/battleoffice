<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $order = new Order();
        $form = $this->createForm(FormOrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $order = $form->getData();
            dd($order);
         $client = $order->getIdClient();
         $client->setClient($order);
         $order = $order->getIdPayment();
         $client->setPayement($order);
         $order = $order->getIdBillingAddress();
         $client->setBillingAddress($order);
         $order = $order->getIdShippingAddress();
         $client->setShippingAddress($order);

            $this->entityManager->persist($order);
            $this->entityManager->flush();

          
            return $this->redirectToRoute('confirmation');
        }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('confirmation', name: 'confirmation')]
    public function confirmation(): Response
    {
      
        return $this->render('landing_page/confirmation.html.twig');
    }
}
