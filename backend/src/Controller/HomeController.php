<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_USER")]
class HomeController extends AbstractController
{
    #[Route('', name: 'app_home')]
    public function index(Security $security): Response
    {
        if (is_null($security->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->redirectToRoute('app_login');
    }

    #[Route('/home', 'app_home')]
    public function home(Security $security)
    {
        $user = $security->getUser();
        if (!is_null($user)) {
            return $this->render('home/index.html.twig', [
                'user' => $user,
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}
