<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"]
        ]);
    }
 
    // TODO Ajouter les information du client pour le traitement stripe
    #[Route('/stripe/payment', name: 'stripe_payment', methods: ['POST'])]
    public function createCharge(Request $request)
    {
        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
                "amount" => 1990,
                "currency" => "EUR",
                "source" => $request->request->get('stripeToken'),
                "line1" => "510 Townsend St",
                "postal_code" => "98140",
                "city" => "San Francisco",
                "state" => "CA",
                "country" => "US",
                "email" => "martin@a.com",
                "name" => "Martin Albert",
                "phone" => "012222222222",
        ]);
        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('stripe', [], Response::HTTP_SEE_OTHER);
    }
}
