<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $repository,
        private CategoryService $service,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route(path: '/categories', name: 'categories')]
    public function index(Request $request): Response
    {
        $categories = $this->service->getCategoryByTitle($request->get('title'));
        return $this->render('category/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    #[Route(path: '/categories/new', name: 'category_create', methods: ['GET', 'POST'])]
    public function create(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->service->setDefaultFields($category);
            $this->entityManager->persist($category);
            $this->entityManager->flush();
            return $this->redirectToRoute('categories');
        }

        return $this->render('category/create.html.twig', [
            'categoryForm' => $form 
        ]);
    }

    #[Route('/categories/{category}/update', name:'category_edit', methods: ['GET', 'POST'])]
    public function edit(Category $category, Request $request)
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            //
        }
        return $this->render('category/update.html.twig', [
            'categoryForm' => $form 
        ]);
    }
}
