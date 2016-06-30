<?php

namespace CoreBundle\Filter;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserFilter extends DateFilter
{
    const USERNAME = 'username';
    const EMAIL = 'email';

    public function __construct(\Symfony\Component\Translation\DataCollectorTranslator $translator, EntityRepository $repo)
    {
        parent::__construct($translator);

        $this->repo = $repo;
    }

    public function filter(QueryBuilder $qb)
    {
        $qb = parent::filter($qb);

        if ($this->has(self::USERNAME)) {
            $username = $this->like(self::USERNAME);

            $qb->andWhere($this->alias . 'username LIKE :username')
                ->setParameter('username', $username);
        }

        if ($this->has(self::EMAIL)) {
            $email = $this->like(self::EMAIL);

            $qb->andWhere($this->alias . 'email LIKE :email')
                ->setParameter('email', $email);
        }

        return $qb;
    }

    protected function getFields()
    {
        $fields = parent::getFields();

        return array_merge([self::USERNAME, self::EMAIL], $fields);
    }
}
