<?php

namespace App\Controller;

use App\Form\ProfileFormType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ProfileController extends AbstractController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private UserService $service
    ) {}
    #[Route('/profile', name: 'profile', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        $user = $this->security->getUser();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->service->updateImage($form->get('image')->getData(), $user);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Your profile data has been successfully updated');
            return $this->redirectToRoute('profile');
        }
        return $this->render('profile/profile.html.twig', [
            'profileForm' => $form,
            'user' => $user
        ]);
    }

    #[Route('/profiles/{slug}','profile_show')]
    public function show(UserInterface $user)
    {
        return $this->render('profile/profile_show.html.twig', [
            'user' => $user
        ]);
    }
}
