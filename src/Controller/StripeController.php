<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;

class StripeController extends AbstractController
{
    #[Route('/stripe/{orderId}/{productPrice}', name: 'app_stripe')]
    public function index(string $orderId, string $productPrice): Response
    {
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
            "amount" =>$formattedProductPrice * 100, // Convert the price to cents
            "currency" => "eur",
            "source" => $request->request->get('stripeToken'),
            "description" => "Binaryboxtuts Payment Test for Order ID: $orderId"
        ]);
    
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
    
        return $this->redirectToRoute('app_stripe', [], Response::HTTP_SEE_OTHER);
    }
}
