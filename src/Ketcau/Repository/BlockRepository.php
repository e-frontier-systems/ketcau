<?php

namespace Ketcau\Repository;

use Doctrine\Persistence\ManagerRegistry as RegistryInterface;
use Ketcau\Entity\Block;

class BlockRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Block::class);
    }



    public function getUnusedBlocks($Blocks)
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->where('b not in (:blocks)')
            ->setParameter('blocks', $Blocks)
            ->getQuery()
            ->getResult();
    }
}