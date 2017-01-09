<?php

namespace Ayigi\EtablissementBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * EtablissementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EtablissementRepository extends EntityRepository
{

	public function EtablissementPays($idPays)    
	{
		$qb = $this->createQueryBuilder('e')
					->leftJoin('e.paysDestination', 'p')
    				->addSelect('p')      
	            	->where('p.id = :idPays')
	            	->setParameter('idPays', $idPays);
	 
			return $qb->getQuery()->getArrayResult();
	}
}