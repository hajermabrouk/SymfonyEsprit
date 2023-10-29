<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\BookType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;



class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    #[Route('/list-book', name: 'list_book')]
    public function listBook(BookRepository $repo): Response
    {
        $result = $repo->findAll();
    
        // Récupérer les livres publiés
        $publishedBooks = $this->getDoctrine()->getRepository(Book::class)->findBy(['published' => true]);
    
        // Compter le nombre de livres publiés et non publiés
        $numPublishedBooks = count($publishedBooks);
        $numUnPublishedBooks = count($this->getDoctrine()->getRepository(Book::class)->findBy(['published' => false]));
    
        if ($numPublishedBooks > 0) {
            return $this->render('book/list-book.html.twig', [
                'result' => $result,
                'publishedBooks' => $publishedBooks,
                'numPublishedBooks' => $numPublishedBooks,
                'numUnPublishedBooks' => $numUnPublishedBooks,
            ]);
        } else {
            // Afficher un message si aucun livre n'a été trouvé
            return $this->render('book/no_books_found.html.twig');
        }
    }
    
    #[Route('/Add-book', name: 'Add_book')]
    public function AddBook(EntityManagerInterface $em,Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute('list_book');
        }

        return $this->render('book/add-book.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Ajouter',
        ]);
    }
    #[Route('/edit-book/{ref}', name: 'edit_book')]
    public function editBook(EntityManagerInterface $em,BookRepository $repository, $ref, Request $request)
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
        
            $em->flush();

            return $this->redirectToRoute('list_book');
        }

        return $this->render('book/edit-book.html.twig', [
            'form' => $form->createView(),
            'button_label' => 'Modifier',
        ]);
    }

    #[Route('/delete-book/{ref}', name: 'delete_book')]
    public function deleteBook($ref, EntityManagerInterface $em,BookRepository $repository)
    {
        $book = $repository->find($ref);

        if (!$book) {
            throw $this->createNotFoundException('Livre non trouvé');
        }

     
        $em->remove($book);
        $em->flush();

        return $this->redirectToRoute('list_book');
    }
    #[Route('/show-book/{ref}', name: 'show_book')]
    public function showBook($ref,BookRepository $repository)
    {
        $book=$repository->find($ref);
        if(!$book)
        {
        return $this->redirectToRoute('list_book');
    }
   return $this->render('book/show.html.twig',[
    'book' => $book]);
}
}





