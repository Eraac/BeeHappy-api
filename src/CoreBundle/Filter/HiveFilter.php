<?php

namespace CoreBundle\Filter;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class HiveFilter extends DateFilter
{
    const NAME = 'name';
    const OWNER = 'owners';

    private $owners;
    private $zipCodes;

    public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator, EntityRepository $repo)
    {
        parent::__construct($translator);

        $this->repo = $repo;
        $this->owners = [];
        $this->zipCodes = [];
    }

    protected function buildParams()
    {
        parent::buildParams();

        if ($this->has(self::OWNER)) {
            $owner = $this->get(self::OWNER);

            if (!is_array($owner)) {
                $this->owners[] = $owner;
            } else {
                $this->owners = $owner;
            }
        }
    }

    public function filter(QueryBuilder $qb)
    {
        $qb = parent::filter($qb);

        if ($this->has(self::NAME)) {
            $name = $this->like(self::NAME);

            $qb->andWhere($this->alias . 'name LIKE :name')
                ->setParameter('name', $name);
        }

        if ($this->has(self::OWNER)) {
            $qb->leftJoin($this->alias . 'owner', 'o')
                ->andWhere('o.username IN (:owners)')
                ->setParameter('owners', $this->owners);
        }

        return $qb;
    }

    protected function getFields()
    {
        $fields = parent::getFields();

        return array_merge([self::NAME], $fields);
    }
}
