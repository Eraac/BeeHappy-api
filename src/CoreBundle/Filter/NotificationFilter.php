<?php

namespace CoreBundle\Filter;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class NotificationFilter extends Filter
{
    const HIVE = 'hives';
    const ALERT = 'alerts';

    private $hives;
    private $alerts;

    public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator, EntityRepository $repo)
    {
        parent::__construct($translator);

        $this->repo = $repo;
        $this->hives = [];
        $this->types = [];
    }

    protected function buildParams()
    {
        parent::buildParams();

        if ($this->has(self::HIVE)) {
            $hive = $this->get(self::HIVE);

            if (!is_array($hive)) {
                $this->hives[] = $hive;
            } else {
                $this->hives = $hive;
            }
        }

        if ($this->has(self::ALERT)) {
            $alert = $this->get(self::ALERT);

            if (!is_array($alert)) {
                $this->alerts[] = $alert;
            } else {
                $this->alerts = $alert;
            }
        }
    }

    public function filter(QueryBuilder $qb)
    {
        $qb = parent::filter($qb);

        if ($this->has(self::HIVE)) {
            $qb->leftJoin($this->alias . 'hives', 'g')
                ->andWhere('g.slug IN (:hives)')
                ->setParameter('hives', $this->hives);
        }

        if ($this->has(self::ALERT)) {
            $qb->leftJoin($this->alias . 'alerts', 'a')
                ->andWhere('a.slug IN (:alerts)')
                ->setParameter('alerts', $this->alerts);
        }

        return $qb;
    }

    protected function getFields()
    {
        return [self::ALERT, self::HIVE, 'sendAt'];
    }
}
