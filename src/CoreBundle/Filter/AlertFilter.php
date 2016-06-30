<?php

namespace CoreBundle\Filter;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use CoreBundle\Entity\Alert;

class AlertFilter extends DateFilter
{
    const NAME = 'name';
    const COMPARISON = 'comparisons';
    const HIVE = 'hives';
    const TYPE = 'types';

    private $comparisons;
    private $hives;
    private $types;

    public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator, EntityRepository $repo)
    {
        parent::__construct($translator);

        $this->repo = $repo;
        $this->comparisons = [];
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

        if ($this->has(self::COMPARISON)) {
            $comparison = $this->get(self::COMPARISON);

            if (!is_array($comparison)) {
                $this->comparisons[] = $comparison;
            } else {
                $this->comparisons = $comparison;
            }
        }

        if ($this->has(self::TYPE)) {
            $type = $this->get(self::TYPE);

            if (!is_array($type)) {
                $this->types[] = $type;
            } else {
                $this->types = $type;
            }
        }
    }

    public function isValid()
    {
        $isValid = parent::isValid();

        if (!$isValid) {
            return false;
        }

        foreach ($this->comparisons as $comparison) {
            if (!in_array($comparison, Alert::$OPERATOR)) {
                $this->error = $this->translator->trans('core.filter.comparison_not_found', ['%field%' => $comparison]);
                return false;
            }
        }

        return true;
    }

    public function filter(QueryBuilder $qb)
    {
        $qb = parent::filter($qb);

        if ($this->has(self::NAME)) {
            $name = $this->like(self::NAME);

            $qb->andWhere($this->alias . 'name LIKE :name')
                ->setParameter('name', $name);
        }

        if ($this->has(self::COMPARISON)) {
            $qb->andWhere($this->alias . 'comparison IN (:comparisons)')
                ->setParameter('comparisons', $this->comparisons);
        }

        if ($this->has(self::HIVE)) {
            $qb->leftJoin($this->alias . 'hives', 'g')
                ->andWhere('g.slug IN (:hives)')
                ->setParameter('hives', $this->hives);
        }

        if ($this->has(self::TYPE)) {
            $qb->leftJoin($this->alias . 'type', 't')
                ->andWhere('t.slug IN (:types)')
                ->setParameter('types', $this->types);
        }

        return $qb;
    }

    protected function getFields()
    {
        $fields = parent::getFields();

        return array_merge([self::NAME, self::TYPE, 'comparison', self::HIVE], $fields);
    }
}
