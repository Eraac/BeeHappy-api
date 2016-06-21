<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\Hive;
use Symfony\Component\EventDispatcher\Event;
use CoreBundle\Entity\Alert;

/**
 * Class AlertTriggeredEvent
 *
 * @package CoreBundle\Event
 */
class AlertTriggeredEvent extends Event
{
    const NAME = 'alert.triggered';

    /**
     * @var Alert
     */
    protected $alert;

    /**
     * @var Hive
     */
    protected $hive;

    public function __construct(Hive $hive, Alert $alert)
    {
        $this->hive = $hive;
        $this->alert = $alert;
    }

    /**
     * @return Alert
     */
    public function getAlert()
    {
        return $this->alert;
    }

    public function getHive()
    {
        return $this->hive;
    }
}
