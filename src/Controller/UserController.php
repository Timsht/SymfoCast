<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Symfony\Component\HttpFoundation\Request;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
        $depotUser = $this->getDoctrine()->getRepository(User::class);
        $user = $depotUser->findOneByUsername( $username );
        
        if ($user === null) {
            throw $this->createNotFoundException("User doesn't exist");
        }

        $post = new Posts();
        $formulaire = $this->createForm(PostType::class, $post);
        
        $formulaire->handleRequest($request);
        
        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $post->setUser($user);
            $post->setAuthor($this->getUser());
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($post);
            $gestionnaire->flush();

            return $this->redirectToRoute("user_profil", array("username" => $username));
        }

        $depotPost = $this->getDoctrine()->getRepository(Posts::class);

        $posts = $depotPost->findBy(array("user" => $user), array('id' => 'DESC'));

        return $this->render("user/user.html.twig", array(
            "user" => $user,
            "formulaire" => $formulaire->createView(),
            "posts" => $posts
        ));
    }

}
