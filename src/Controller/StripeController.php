<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(SessionInterface $session): Response
    {
        $orderId = $session->get('orderId');
        $productPrice = $session->get('productPrice');

        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            'orderId' => $orderId,
            'productPrice' => $productPrice,
        ]);
        
    }
    

    #[Route('/stripe/create-charge', name: 'app_stripe_charge', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
    
        $orderId = $request->request->get('orderId');
        $productPrice = $request->request->get('productPrice');

        $formattedProductPrice = str_replace(",", ".", str_replace(".", "", $productPrice));    
        Stripe\Charge::create([
            "amount" =>$formattedProductPrice, // Convert the price to cents
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "Binaryboxtuts Payment Test for Order ID: $orderId"
        ]);
    
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
    
        return $this->render('landing_page/confirmation.html.twig', [
            'orderId' => $orderId,
            'productPrice' => $productPrice,
        ]); 
    }
}
