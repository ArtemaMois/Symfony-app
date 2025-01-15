<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Service\RegisterService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    public function __construct(private EmailVerifier $emailVerifier)
    {
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, RegisterService $registerService, Security $security): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $registerService->setUserData($user, $form->get('plainPassword')->getData());
            $entityManager->persist($user);
            $entityManager->flush();
            $security->login($user);

            return $this->redirectToRoute('posts_self');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
