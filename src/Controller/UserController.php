<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
Use Symfony\Component\HttpFoundation\Request;
Use App\Entity\User;
Use App\Form\RegistrationFormType;



class UserController extends AbstractController
{

    /**
     * paramUser
     *
     * @Route("/param/{id}", name="parm_user")
     * 
     * @return void
     */
    public function paramUser(Request $request, $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $depot = $this->getDoctrine()->getManager();

        $user = $depot->getRepository(User::class)->find($id);
        $formulaire = $this->createForm(RegistrationFormType::class, $user);

        $formulaire->handleRequest($request);

        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $depot->flush();
            return $this->redirectToRoute("/$user->getUsername()");
        }

        return $this->render("user/parm.html.twig",array(
            'formulaire' => $formulaire->createView()
        ));

        // return $this->render("user/parm.html.twig");
    }

    /**
     * User page
     *
     * @Route("/{user}", name="user_profil")
     * 
     * @return void
     */
    public function profil(string $user)
    {
        $depot = $this->getDoctrine()->getRepository(User::class);

        $appUser = $depot->findBy(["username" => $user]);
        
        if ($appUser === []) {
            return $this->render("bundles/TwigBundle/Exception/error404.html.twig");
        }
        
        return $this->render('user/user.html.twig', array(
            "user" => $appUser[0]
        ));
    }

}
