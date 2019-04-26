<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
Use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Symfony\Component\HttpFoundation\Request;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
Use App\Entity\User;
Use App\Entity\Posts;
Use App\Entity\Comments;
Use App\Form\RegistrationFormType;
Use App\Form\CommentPostType;
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

        $formulaire = $this->createForm(RegistrationFormType::class, $this->getUser());

        if($formulaire->isSubmitted() && $formulaire->isValid()) {
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($post);
            $gestionnaire->flush();

            return $this->redirectToRoute("user_profil", array("username" => $username));
        }

        return $this->render("user/parm.html.twig", array(
            "formulaire" => $formulaire->createView()
        ));
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $depotUser = $this->getDoctrine()->getRepository(User::class);
        $users = $depotUser->findAll();
        $user = $depotUser->findOneByUsername( $username );
        
        if ($user === null) {
            throw $this->createNotFoundException("User doesn't exist");
        }

        // Formulaire de posts
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
        // recherche des posts des user par ordre antéchronologique
        $posts = $depotPost->findBy(array("user" => $user), array('id' => 'DESC'));

        // $depotCom = $this->getDoctrine()->getRepository(Comments::class);
        // dd($depotCom->findBy(array("post" => $post)));
        // $comments = $depotCom->findBy(array("post_id" => $depot->getId()), array('id' => 'DESC'));


        return $this->render("user/user.html.twig", array(
            "user" => $user,
            "users" => $users,
            "formulaire" => $formulaire->createView(),
            "posts" => $posts,
        ));
    }

    /**
     * commentPost
     *
     * @Route("/profil/{username}/{id}", name="comment")
     * @return void
     */
    public function commentPost(Request $request, $id)
    {
        $depot = $this->getDoctrine()->getRepository(Posts::class);
        $post = $depot->find($id);

        $comment = new Comments();
        // $formComment = $this->get("form.factory")->createNamed($posts[0]->getId(), CommentPostType::class, $comment);
        $formComment = $this->createForm(CommentPostType::class, $comment);
        $formComment->handleRequest($request);


        if($formComment->isSubmitted() && $formComment->isValid()) {
            $comment->setAuthor($this->getUser());
            $comment->setPost($post);
            $gestionnaire = $this->getDoctrine()->getManager();
            $gestionnaire->persist($comment);
            $gestionnaire->flush();

            return $this->redirectToRoute("user_profil", array("username" => $this->getUser()->getUsername()));
        }


        return $this->render("/user/comment.html.twig", array(
            "formComment" => $formComment->createView()
        ));
    }

}
