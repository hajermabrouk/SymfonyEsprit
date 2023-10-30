<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countPublishedBooks(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(b)
            FROM App\Entity\Book b
            WHERE b.published = 1'
        );
        return $query->getSingleScalarResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function countUnpublishedBooks(): int
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(b)
            FROM App\Entity\Book b
            WHERE b.published = 0'
        );
        return $query->getSingleScalarResult();
    }

    public function searchBookByRef(string $ref){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            WHERE b.ref = :ref'
        )->setParameter('ref',$ref);
        return $query->getResult();
    }

    public function findAllOrderByAuthor(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            ORDER BY b.author'
        );
        return $query->getResult();
    }

    public function findBookPublishedBefore2023AndAuthorHasMoreThan10Books(){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            JOIN App\Entity\Author a
            WHERE b.published = 1 AND b.publicationDate < 2023 AND a.nb_books > 10'
        );
        return $query->getResult();
    }

    public function countBooksByCategory(string $category){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT COUNT(b)
            FROM App\Entity\Book b
            WHERE b.category = :category'
        )->setParameter('category',$category);
        return $query->getSingleScalarResult();
    }

    public function findBooksPublishedBetweenTwoDates(string $date1, string $date2){
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT b
            FROM App\Entity\Book b
            WHERE b.publicationDate BETWEEN :date1 AND :date2'
        )->setParameter('date1',$date1)->setParameter('date2',$date2);
        return $query->getResult();
    }


}
