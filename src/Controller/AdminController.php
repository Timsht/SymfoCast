<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
Use App\Entity\User;
Use App\Form\UpdateUsersType;
Use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * admin
     *
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     * @return void
     */
    public function admin(Request $request)
    {
        $depot = $this->getDoctrine()->getRepository(User::class);

        $users = $depot->findAll();
        $roles = $this->getParameter('security.role_hierarchy.roles');

        // foreach($users as $key => $user) {
        //     $formulaire = array();
        //     $formulaire[] = $this->createForm(UpdateUsersType::class, $user);    
        //     $formulaire[$key]->handleRequest($request);
            
        //     if($formulaire[$key]->isSubmitted() && $formulaire[$key]->isValid()) {
        //         $updateUsers->setArticle($article);
        //         $gestionnaire = $this->getDoctrine()->getManager();
        //         $gestionnaire->persist($updateUsers);
        //         $gestionnaire->flush();
        //     }
        // }

        return $this->render("admin/admin.html.twig", array(
            'users' => $users,
            'security' => $roles
        ));
    }
}
