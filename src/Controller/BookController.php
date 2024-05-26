<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class BookController extends AbstractController
{
    #[Route('/library', name: 'library')]
    public function viewAllBooks(BookRepository $bookRepository): Response
    {
        $books = $bookRepository->findAll();

        $data = ['books' => $books];

        return $this->render('book/index.html.twig', $data);
    }

    #[Route('/book/create', name: 'book_create', methods: ["POST"])]
    public function createBook(Request $request, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($request->request->get('title'));
        $book->setAuthor($request->request->get('author'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setImage($request->request->get('image'));

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }

    #[Route('/book/delete/{id}', name: 'book_delete', methods: ["POST"])]
    public function deleteBook(ManagerRegistry $doctrine, int $bookId): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookId);

        $entityManager->remove($book);
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }

    #[Route('/library/{id}', name: 'book_by_id')]
    public function showBookById(bookRepository $bookRepository, int $bookId): Response
    {
        $book = $bookRepository->find($bookId);

        $data = ['book' => $book];

        return $this->render('book/book.html.twig', $data);
    }

    #[Route('/library/update/{id}', name: 'show_book_update', methods: ["GET"])]
    public function showUpdateBook(bookRepository $bookRepository, int $bookId): Response
    {
        $book = $bookRepository->find($bookId);

        $data = ['book' => $book];

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/book/update/{id}', name: 'book_update', methods: ["POST"])]
    public function updateBook(ManagerRegistry $doctrine, Request $request, int $bookId): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookId);

        $book->setTitle($request->request->get('title'));
        $book->setAuthor($request->request->get('author'));
        $book->setIsbn($request->request->get('isbn'));
        $book->setImage($request->request->get('image'));

        $entityManager->flush();

        return $this->redirectToRoute('library');
    }
}
