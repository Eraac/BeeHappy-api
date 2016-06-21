<?php

namespace CoreBundle\Service;

use Doctrine\ORM\EntityManager;
use CoreBundle\Entity\HistoryNotification;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\Alert;

class HistoryNotificationHandler
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param Hive $hive
     * @param Alert $alert
     * @return bool
     */
    public function canSendNotification(Hive $hive, Alert $alert)
    {
        /** @var \CoreBundle\Repository\HistoryNotificationRepository $repo */
        $repo = $this->em->getRepository('CoreBundle:HistoryNotification');

        $count = $repo->countNotificationLast24Hours($hive, $alert);

        return 0 == $count;
    }

    public function addHistoryNotification(Hive $hive, Alert $alert)
    {
        $notification = new HistoryNotification();
        $notification->setHive($hive)
                    ->setAlert($alert)
                    ->setSendAt(new \DateTime());

        $this->em->persist($notification);
        $this->em->flush();
    }
}
