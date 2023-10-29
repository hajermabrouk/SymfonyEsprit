<?php

namespace App\Controller;
use App\Repository\AuthorRepository;
use App\Entity\Author;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/list-author', name: 'list_author')]
    public function listAuthor(AuthorRepository $repo): Response
    {
        $result = $repo->findAll();
        return $this->render('author/list-author.html.twig', [
            'response' => $result
        ]);
    }
    #[Route('/Add-author', name: 'Add_author')]
    public function AddAuthor(EntityManagerInterface $em,Request $request): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('list_author');
        }

        return $this->render('author/add-author.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Ajouter',
        ]);
    }
    #[Route('/edit-author/{id}', name: 'edit_author')]
    public function editAuthor(EntityManagerInterface $em, AuthorRepository $repository, $id, Request $request)
    {
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

   
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();

           
            return $this->redirectToRoute("list_author");
        }

        return $this->render('author/edit-author.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Modifier',
        ]);
    }
    #[Route('/delete-author/{id}', name: 'delete_author')]
    public function deleteAuthor($id, EntityManagerInterface $em,AuthorRepository $repository)
    {
        $author = $repository->find($id);

        if (!$author) {
            throw $this->createNotFoundException('Auteur non trouvé');
        }

     
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute("list_author");
    }
   
}


