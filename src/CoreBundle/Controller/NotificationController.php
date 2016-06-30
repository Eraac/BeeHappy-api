<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use Symfony\Component\HttpFoundation\Request;

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

    protected function getRepositoryName()
    {
        return 'CoreBundle:HistoryNotification';
    }
}
