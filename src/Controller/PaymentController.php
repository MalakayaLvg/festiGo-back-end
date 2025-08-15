<?php

namespace App\Controller;

use App\Repository\VisitorRepository;
use App\Service\QrCodeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api')]
class PaymentController extends AbstractController
{
    #[Route('/payment/display/qrcode', name: 'app_payment_display_qrcode', methods: 'GET')]
    public function displayPaymentQrCode(QrCodeService $qrCodeService, VisitorRepository $visitorRepository): Response
    {
        $user = $this->getUser();
        if (!$user){
            return $this->json(['error'=>'Utilisateur non identifie'],301);
        }
        $visitor = $visitorRepository->find($user);

        $data = [
            'id'=>$visitor->getId(),
            'email'=>$visitor->getEmail(),
            'creditsBalance'=>$visitor->getCreditsBalance(),

        ];
        $qrcode = $qrCodeService->generateQrCode(json_encode($data));
        $qrcodeImage = base64_decode($qrcode);
        return new Response($qrcodeImage,'200',[
            'Content-Type'=>'image/png',
            'Content-Disposition'=>'inline; filename="qrcode.png"'
        ]);
    }



}
