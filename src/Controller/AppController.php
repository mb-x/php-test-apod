<?php

namespace App\Controller;

use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{

    /**
     * @Route("/", name="home")
     */
    public function pictureOfTheDay(ImageRepository $imageRepository)
    {
        $image = $imageRepository->getLastImage();

        return $this->render('app/picture.html.twig', [
           'image' => $image,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        return $this->render('app/login.html.twig');
    }

}