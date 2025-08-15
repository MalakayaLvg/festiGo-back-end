<?php

namespace App\Controller;

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Label\Font\OpenSans;
use Endroid\QrCode\Label\LabelAlignment;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class TestController extends AbstractController
{
    #[Route('/test/generate-qrcode', name: 'app_test_generate_qrcode')]
    public function generateQrCode(): Response
    {
        $builder = new Builder(
            writer: new PngWriter(),
            writerOptions: [],
            validateResult: false,
            data: 'Custom QR code contents',
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 300,
            margin: 10,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            labelText: 'This is the label',
            labelFont: new OpenSans(20),
            labelAlignment: LabelAlignment::Center,
        );

        $result = $builder->build();
        $qrcode = base64_encode($result->getString());

        return $this->render('ticket/index.html.twig',
            [
                'qrcode'=>$qrcode
            ]);
    }

    #[\Symfony\Component\Routing\Annotation\Route('/test/payment', name: 'app_test_payment')]
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
