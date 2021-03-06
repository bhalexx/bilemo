<?php

namespace AppBundle\Repository;

/**
 * MobileRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MobileRepository extends AbstractRepository
{
    public function search($manufacturerId = null, $order = 'asc', $limit = 20, $offset = 0)
    {
        $qb = $this
            ->createQueryBuilder('m')
            ->select('m')
            ->orderBy('m.name', $order)
        ;

        if ($manufacturerId) {
        	$qb
        		->where('m.manufacturer = :manufacturerId')
	            ->setParameter('manufacturerId', $manufacturerId)
			;
        }

        return $this->paginate($qb, $limit, $offset);
    }
}
