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
        $title = (string) $request->request->get('title');
        $author = (string) $request->request->get('author');
        $isbn = (string) $request->request->get('isbn');
        $image = $request->request->get('image');

        $entityManager = $doctrine->getManager();

        $book = new Book();
        $book->setTitle($title);
        $book->setAuthor($author);
        $book->setIsbn($isbn);
        $book->setImage($image !== null ? (string) $image : null);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('library');
    }

    #[Route('/book/delete/{bookId}', name: 'book_delete', methods: ["POST"])]
    public function deleteBook(ManagerRegistry $doctrine, int $bookId): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookId);

        if ($book !== null) {
            $entityManager->remove($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('library');
    }

    #[Route('/library/{bookId}', name: 'book_by_id')]
    public function showBookById(bookRepository $bookRepository, int $bookId): Response
    {
        $book = $bookRepository->find($bookId);

        $data = ['book' => $book];

        return $this->render('book/book.html.twig', $data);
    }

    #[Route('/library/update/{bookId}', name: 'show_book_update', methods: ["GET"])]
    public function showUpdateBook(bookRepository $bookRepository, int $bookId): Response
    {
        $book = $bookRepository->find($bookId);

        $data = ['book' => $book];

        return $this->render('book/update.html.twig', $data);
    }

    #[Route('/book/update/{bookId}', name: 'book_update', methods: ["POST"])]
    public function updateBook(ManagerRegistry $doctrine, Request $request, int $bookId): Response
    {
        $entityManager = $doctrine->getManager();
        $book = $entityManager->getRepository(Book::class)->find($bookId);

        if ($book !== null) {
            $title = (string) $request->request->get('title');
            $author = (string) $request->request->get('author');
            $isbn = (string) $request->request->get('isbn');
            $image = $request->request->get('image');

            $book->setTitle($title);
            $book->setAuthor($author);
            $book->setIsbn($isbn);
            $book->setImage($image !== null ? (string) $image : null);

            $entityManager->flush();
        }

        return $this->redirectToRoute('library');
    }
}
