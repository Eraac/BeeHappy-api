<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\Alert;
use CoreBundle\Security\HiveVoter;
use CoreBundle\Security\AlertVoter;

class HiveAlertController extends CoreController
{
    /**
     * @View(serializerGroups={"Default"})
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function getAlertsAction(Hive $hive)
    {
        $alerts = $hive->getAlerts();

        $this->isGranted(HiveVoter::EDIT, $hive); // TODO increase

        return [
            'alerts' => $alerts,
        ];
    }

    /**
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     * @ParamConverter("alert", options={"mapping": {"alert": "slug"}})
     */
    public function postAlertsAction(Hive $hive, Alert $alert)
    {
        $this->isGranted(HiveVoter::EDIT, $hive);
        $this->isGranted(AlertVoter::VIEW, $alert);

        $alert->addHive($hive);

        $this->getManager()->flush();

        return new JsonResponse([], Codes::HTTP_OK);
    }

    /**
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     * @ParamConverter("alert", options={"mapping": {"alert": "slug"}})
     */
    public function deleteAlertsAction(Hive $hive, Alert $alert)
    {
        $this->isGranted(HiveVoter::EDIT, $hive);
        $this->isGranted(AlertVoter::VIEW, $alert);

        $alert->removeHive($hive);

        $this->getManager()->flush();
    }

    protected function getRepositoryName()
    {
        return 'CoreBundle:Alert';
    }
}
