<?php

namespace App\Repository;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Biography;

/**
 * Biography repository
 */
class BiographyRepository extends ServiceEntityRepository implements iRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Biography::class);
    }
	
	public function getDatatablesForIndex($iDisplayStart, $iDisplayLength, $sortByColumn, $sortDirColumn, $sSearch, $count = false)
	{
		$qb = $this->createQueryBuilder("pf");

		$aColumns = array( 'pf.id', 'pf.title', 'la.title', 'pf.id');
		
		$qb->leftjoin("pf.language", "la");
		
		if(!empty($sortDirColumn))
		   $qb->orderBy($aColumns[$sortByColumn[0]], $sortDirColumn[0]);
		
		if(!empty($sSearch))
		{
			$search = "%".$sSearch."%";
			$qb->where('pf.title LIKE :search')
			   ->setParameter('search', $search);
		}
		if($count)
		{
			$qb->select("COUNT(pf) AS count");
			return $qb->getQuery()->getSingleScalarResult();
		}
		else
			$qb->setFirstResult($iDisplayStart)->setMaxResults($iDisplayLength);

		return $qb->getQuery()->getResult();
	}
	
	public function getPoemByAuthors()
	{
		$qb = $this->createQueryBuilder("pf");
	
		$qb->groupBy("pf.biography_id");
		   
		return $qb->getQuery()->getResult();
	}
	
	public function findAllForChoice($locale)
	{
		$qb = $this->createQueryBuilder("pf");
		
		$qb->select("pf.id AS id, pf.title AS title")
		   ->leftjoin("pf.language", "la")
		   ->where('la.abbreviation = :locale')
		   ->setParameter('locale', $locale)
		   ->orderBy("title", "ASC");

		return $qb;
	}
	
	public function checkForDoubloon($entity)
	{
		$qb = $this->createQueryBuilder("pf");

		$qb->select("COUNT(pf) AS number")
		   ->leftjoin("pf.language", "la")
		   ->where("pf.slug = :slug")
		   ->setParameter('slug', $entity->getSlug())
		   ->andWhere("la.id = :idLanguage")
		   ->setParameter("idLanguage", $entity->getLanguage());

		if($entity->getId() != null)
		{
			$qb->andWhere("pf.id != :id")
			   ->setParameter("id", $entity->getId());
		}
		return $qb->getQuery()->getSingleScalarResult();
	}

	// Combobox
	public function getDatasCombobox($params, $locale, $count = false)
	{
		$qb = $this->createQueryBuilder("b");

		if(!empty($locale))
			$qb->leftjoin("b.language", "l")
			   ->andWhere("l.id = :localeId")
			   ->setParameter("localeId", $locale);
		
		if(array_key_exists("pkey_val", $params))
		{
			$qb->select("b.id, b.title")
			   ->andWhere('b.id = :id')
			   ->setParameter('id', $params['pkey_val']);
			   
			return $qb->getQuery()->getOneOrNullResult();
		}
		
		$params['offset']  = ($params['page_num'] - 1) * $params['per_page'];

		$qb->select("b.id, b.title")
		   ->andWhere("b.title LIKE :title")
		   ->setParameter("title", "%".implode(' ', $params['q_word'])."%")
		   ->setMaxResults($params['per_page'])
		   ->setFirstResult($params['offset'])
		   ;
		
		if($count)
		{
			$qb->select("COUNT(b.id)")
			   ->andWhere("b.title LIKE :title")
			   ->setParameter("title", "%".implode(' ', $params['q_word'])."%")
			   ;
			   
			return $qb->getQuery()->getSingleScalarResult();
		}

		return $qb->getQuery()->getResult();
	}
}