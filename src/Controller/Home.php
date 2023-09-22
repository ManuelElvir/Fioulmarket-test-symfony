<?php

namespace App\Controller;

use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     * @return Response
     */
    public function index(ImageService $imageService): Response
    {
        $url = 'http://www.commitstrip.com/en/feed/';
        
        $imageUrls = $imageService->fetchImageUrls($url);

        $images = [];
        foreach ($imageUrls as $imageUrl) {
            try {
                $imageSrc = $imageService->getImageFromUrl($imageUrl);
                if ($imageSrc) {
                    $images[] = $imageSrc;
                }
            } catch (\Exception $e) {
                // Gérer les erreurs éventuelles ici
            }
        }

        return $this->render('default/index.html.twig', ['images' => $images]);
    }
    
}
