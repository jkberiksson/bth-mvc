<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function viewAllBooks(
        BookRepository $BookRepository
    ): Response {
        $books = $BookRepository->findAll();

        $data = [
            'books' => $books
        ];

        return $this->render('book/index.html.twig', $data);
    }

    #[Route('/book/create', name: 'book_create', methods: ["POST"])]
    public function createBook(
        ManagerRegistry $doctrine
    ): Response {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle("hej");
        $book->setIsbn("hej");
        $book->setAuthor("hej");
        $book->setImage("hej");

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }
}
