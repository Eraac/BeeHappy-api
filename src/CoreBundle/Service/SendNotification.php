<?php

namespace CoreBundle\Service;

use CoreBundle\Entity\Hive;
use Symfony\Component\Translation\DataCollectorTranslator;
use CoreBundle\Entity\Alert;
use CoreBundle\Service\HistoryNotificationHandler;
use Corebundle\Service\Notification\EmailNotification;

class SendNotification
{
    /**
     * @var HistoryNotificationHandler
     */
    private $historyNotificationHandler;

    /**
     * @var EmailNotification
     */
    private $emailNotification;

    /**
     * @var DataCollectorTranslator
     */
    private $translator;

    public function __construct(HistoryNotificationHandler $historyNotificationHandler, EmailNotification $emailNotification, DataCollectorTranslator $translator)
    {
        $this->historyNotificationHandler = $historyNotificationHandler;
        $this->emailNotification = $emailNotification;
        $this->translator = $translator;
    }

    public function send(Hive $hive, Alert $alert)
    {
        if (!$this->historyNotificationHandler->canSendNotification($hive, $alert)) {
            return;
        }

        $title = $this->createTitle($hive);
        $message = $this->createMessage($alert);
        $email = $alert->getOwner()->getEmail();

        $this->emailNotification->send($title, $message, $email);

        $this->historyNotificationHandler->addHistoryNotification($hive, $alert);
    }

    private function createMessage(Alert $alert)
    {
        return $alert->getMessage();
    }

    private function createTitle(Hive $hive)
    {
        return $this->translator->trans('core.alert.title', ['%hive%' => $hive->getName()]);
    }
}