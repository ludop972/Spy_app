<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Hideouts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Hideouts>
 *
 * @method Hideouts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hideouts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hideouts[]    findAll()
 * @method Hideouts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HideoutsRepository extends ServiceEntityRepository
{

    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Hideouts::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Hideouts $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Hideouts $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWidthSearch(Search $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('h')
            ->select('c','h')
            ->join('h.country', 'c');

        if (!empty($search->string)) {
            $query = $query
                ->andWhere('h.alias LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        if (!empty($search->nationalities)) {
            $query = $query
                ->andWhere('h.country IN (:country)')
                ->setParameter('country', $search->nationalities);
        }

        $query->getQuery()->getResult();
        return $this->paginator->paginate($query,$search->page,4);
    }
    // /**
    //  * @return Hideouts[] Returns an array of Hideouts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Hideouts
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
