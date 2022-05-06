<?php

namespace App\Repository;

use App\Classe\Search;
use App\Entity\Agent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Agent>
 *
 * @method Agent|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agent|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agent[]    findAll()
 * @method Agent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgentRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Agent::class);
        $this->paginator = $paginator;
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Agent $entity, bool $flush = true): void
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
    public function remove(Agent $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findWidthSearch(Search $search, $filters = null, $filters2 = null): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('a')
            ->select('c', 'a')
            ->select('s','a')
            ->join('a.nationality', 'c') // on joint les propriétés de l'agent ( relation )
            ->join('a.specialities', 's'); // on joint les propriétés de l'agent ( relation )

        if($filters !== null){
            $query = $query
                ->andWhere('a.nationality IN (:nationality)')
                ->setParameter('nationality', array_filter($filters));
        }

        if($filters2 !== null){
            $query = $query
                ->andWhere('s.id IN (:specialities)')
                ->setParameter('specialities',array_filter($filters2));
        }

        if (!empty($search->string)) {
            $query = $query
                ->andWhere('a.lastname LIKE :string')
                ->setParameter('string', "%{$search->string}%");
        }

        if (!empty($search->nationalities)) {
            $query = $query
                ->andWhere('c.id IN (:nationality)')
                ->setParameter('nationality', $search->nationalities);
        }

        if (!empty($search->specialities)) {
            $query = $query
                ->andWhere('s.id IN (:specialities)')
                ->setParameter('specialities', $search->specialities);
        }

        $query->getQuery();
        return $this->paginator->paginate($query,$search->page,4);
    }


    // /**
    //  * @return Agent[] Returns an array of Agent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Agent
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
