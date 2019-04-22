<?php
namespace App\Controller;

Use Symfony\Component\HttpFoundation\Response;
Use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
Use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
Use App\Entity\User;

class AdminController extends AbstractController
{
    /**
     * admin
     *
     * @Route("/admin", name="admin")
     * @IsGranted("ROLE_ADMIN")
     * @return void
     */
    public function admin()
    {
        $depot = $this->getDoctrine()->getRepository(User::class);

        $users = $depot->findAll();

        $roles = $this->getParameter('security.role_hierarchy.roles');

        return $this->render("admin/admin.html.twig", array(
            'users' => $users,
            'security' => $roles
        ));
    }
}
