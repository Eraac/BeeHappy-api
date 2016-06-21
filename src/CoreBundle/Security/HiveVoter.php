<?php

namespace CoreBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use CoreBundle\Entity\Hive;

class HiveVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return parent::supports($attribute, $subject) && $subject instanceof Hive;
    }

    /**
     * @param Hive $entity
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function canView($entity, TokenInterface $token)
    {
        return $this->isOwner($entity, $this->getUser($token));
    }

    /**
     * @param Hive $entity
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function canCreate($entity, TokenInterface $token)
    {
        return $this->isConnected($token);
    }

    /**
     * @param Hive $entity
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function canEdit($entity, TokenInterface $token)
    {
        return $this->isOwner($entity, $this->getUser($token));
    }

    /**
     * @param Hive $entity
     * @param TokenInterface $token
     *
     * @return boolean
     */
    protected function canDelete($entity, TokenInterface $token)
    {
        return $this->canEdit($entity, $token);
    }
}
