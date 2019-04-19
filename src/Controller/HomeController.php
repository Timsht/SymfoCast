<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * Home page
     *
     * @Route("/", name="homepage")
     * 
     * @return void
     */
    public function home()
    {
        return $this->render('home/home.html.twig');
    }
}
