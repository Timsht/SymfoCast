<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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

    /**
     * mentions
     *
     * @Route("/mentions", name="mentions")
     * @return void
     */
    public function mentions()
    {
        return $this->render("home/mentions.html.twig");
    }

    /**
     * contact
     *
     * @Route("/contact", name="contact")
     * @return void
     */
    public function contact()
    {
        return $this->render("home/contact.html.twig");
    }
}
