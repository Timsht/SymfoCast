<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
Use Symfony\Component\HttpFoundation\Request;
Use App\Entity\User;
Use App\Entity\Posts;
Use App\Form\RegistrationFormType;
Use App\Form\PostType;



class UserController extends AbstractController
{

    /**
     * paramUser
     *
     * @Route("/param/{id}", name="parm_user")
     * 
     * @return void
     */
    public function paramUser(Request $request, int $id)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $depot = $this->getDoctrine()->getManager();

        return $this->render("user/parm.html.twig");
    }

    /**
     * User page
     *
     * @Route("/profil/{username}", name="user_profil")
     * 
     * @return void
     */
    public function profil(Request $request, string $username)
    {
        $depot = $this->getDoctrine()->getRepository(User::class);
        $user = $depot->findOneByUsername( $username );
        
        if ($user === null) {
            throw $this->createNotFoundException("User doesn't exist");
        }

        $post = new Posts();
        $formulaire = $this->createForm(PostType::class, $post);
        
        $formulaire->handleRequest($request);
        
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $post->setUser($this->getUser());
            $post->setAuthor($user);
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($post);
            $gestionnaire->flush();

            return $this->redirectToRoute("user_profil", array("username" => $username));
        }

        return $this->render("user/user.html.twig", array(
            "user" => $user,
            "formulaire" => $formulaire->createView()
        ));
    }

}
