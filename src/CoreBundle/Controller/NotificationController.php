<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Hive;
use CoreBundle\Security\HiveVoter;

class NotificationController extends CoreController
{
    /**
     * @View(serializerGroups={"Default"})
     */
    public function getNotificationsAction(Request $request)
    {
        /** @var \CoreBundle\Filter\NotificationFilter $filter */
        $filter = $this->getFilter('core.notification_filter', $request);

        $query = $filter->getQuery('queryBuilderNotification', [$this->getUser()]);

        $pagination = $this->getPagination($request, $query, 'notification');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'notifications' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    /**
     * @View(serializerGroups={"Default"})
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function getNotificationAction(Request $request, Hive $hive)
    {
        $this->isGranted(HiveVoter::VIEW, $hive);

        /** @var \CoreBundle\Filter\NotificationFilter $filter */
        $filter = $this->getFilter('core.notification_filter', $request);

        $query = $filter->getQuery('queryBuilderNotificationByHive', [$hive]);

        $pagination = $this->getPagination($request, $query, 'notification');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'notifications' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    protected function getRepositoryName()
    {
        return 'CoreBundle:HistoryNotification';
    }
}
