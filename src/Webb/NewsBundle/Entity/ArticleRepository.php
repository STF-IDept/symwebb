<?php

namespace Webb\NewsBundle\Entity;
use Doctrine\ORM\EntityRepository;

class ArticleRepository extends EntityRepository
{
    public function getArticlesByTags($tags)
    {
        if(!is_array($tags)) {
            $tags = array($tags);
        }

        $qb = $this->createQueryBuilder('a');
        $qb -> join('a.tags', 't')
            -> where($qb->expr()->in('t.name', $tags));
        return $qb;
    }
}