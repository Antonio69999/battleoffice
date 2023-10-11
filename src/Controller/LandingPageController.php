<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\FormOrderType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingPageController extends AbstractController
{

    #[Route('/', name: 'landing_page', methods: ['GET', 'POST'])]

    public function index(Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository ) :Response
    {

        $products = $productRepository->findAll();

        $order = new Order();
        $form = $this->createForm(FormOrderType::class, $order);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
           
            $order = $form->getData();
            dd($order);
           
            $client = $order->getClient();
         
            $client->setClient($order);
            
           

            $entityManager->persist($order);
            $entityManager->flush();


         return $this->redirectToRoute('confirmation');
         }

        return $this->render('landing_page/index_new.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }

    #[Route('confirmation', name: 'confirmation')]
    public function confirmation(): Response
    {
       return $this->render('landing_page/confirmation.html.twig');
    }
}
