<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

abstract class AbstractRepository extends EntityRepository
{
    protected function paginate(QueryBuilder $qb, $limit = 20, $offset = 0)
    {
        if($limit == 0) {
            throw new \LogicException('$limit must be greater than 0.');
        }

        //Instantiates the pagination object with the result of the query
        $pager = new Pagerfanta(new DoctrineORMAdapter($qb));

        //Sets max data per page
        $pager->setMaxPerPage($limit);

        //Sets the current page
        $pager->setCurrentPage($offset);

        return $pager;
    }
}
