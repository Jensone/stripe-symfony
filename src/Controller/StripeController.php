<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Stripe;
 
class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'stripe')]
    public function index(UserRepository $userRepository): Response
    {
        // Récupération des informations du client
        $userInfo = $userRepository->findOneBy(
            ['id' => 2]
        );

        return $this->render('stripe/stripe.html.twig', [
            'stripe_key' => $_ENV["STRIPE_KEY"],
            "name" => $userInfo->getName(),
            "email" => $userInfo->getEmail(),
            "phone" => $userInfo->getPhone()
        ]);
    }
 
    // TODO Ajouter les information du client pour le traitement stripe
    #[Route('/stripe/payment', name: 'stripe_payment', methods: ['POST'])]
    public function createCharge(
        Request $request
        ): Response
    {
        
        // Montant de la commande
        $amount = 1990;

        Stripe\Stripe::setApiKey($_ENV["STRIPE_SECRET"]);
        Stripe\Charge::create([
            "amount" => $amount,
            "currency" => "EUR",
            "source" => $request->request->get('stripeToken'),
            // "line1" => "510 Townsend St",
            // "postal_code" => "98140",
            // "city" => "San Francisco",
            // "state" => "CA",
            // "country" => "US",
        ]);

        $this->addFlash(
            'success',
            'Payment Successful!'
        );
        return $this->redirectToRoute('stripe', [], Response::HTTP_SEE_OTHER);
    }
}
