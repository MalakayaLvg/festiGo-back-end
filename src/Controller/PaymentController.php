<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/test/payment', name: 'app_test_payment')]
    public function testPayment(): Response
    {
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

        $paymentIntent = $stripe->paymentIntents->create([
            'amount' => 50,
            'currency' => 'eur',
            'payment_method' => 'pm_card_visa',
            'payment_method_types' => ['card'],
        ]);

        return $this->json($paymentIntent,200);
    }
    #[Route('/test/create-product', name: 'app_test_create_product')]
    public function testCreateProduct(): Response
    {
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

        $product = $stripe->products->create([
            'name'=>'billet de train',
            'description'=>'un billet de train'
        ]);

        $price = $stripe->prices->create([
           'unit_amount'=>5000,
           'currency'=>'eur',
           'product'=>$product['id']
        ]);

        return $this->json($product);
    }

    #[Route('/test/checkout-session', name: 'app_test_checkout_session', methods: 'POST')]
    public function testCreateCheckSession(): Response
    {
        $stripe = new \Stripe\StripeClient($_ENV['STRIPE_SECRET_KEY']);

        $checkout_session = $stripe->checkout->sessions->create([
           'line_items'=>[[
               'price_data'=>[
                   'currency'=>'eur',
                   'product_data'=>[
                       'name'=>'T-shirt',
                   ],
                   'unit_amount' => 5000,
               ],
               'quantity'=>1,
           ]],
            'mode'=>'payment',
            'success_url' => $this->generateUrl('app_checkout_success',[],UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('app_checkout_cancel',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->json($checkout_session->url,303);
    }

    #[Route('/test/checkout/success', name: 'app_checkout_success')]
    public function checkoutSuccess(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/test/checkout/cancel', name: 'app_checkout_cancel')]
    public function checkoutCancel(): Response
    {
        return $this->render('payment/cancel.html.twig');
    }

}
