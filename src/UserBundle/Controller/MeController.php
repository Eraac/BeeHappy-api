<?php

namespace UserBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use CoreBundle\Controller\CoreController;
use Symfony\Component\HttpFoundation\Request;

class MeController extends CoreController
{
    /**
     * @View(serializerGroups={"Default", "details-user", "me"})
     */
    public function getMeAction()
    {
        return [
            'user' => $this->getUser(),
        ];
    }

    /**
     * @View(serializerGroups={"Default", "me-hive"})
     */
    public function getMeHivesAction(Request $request)
    {
        /** @var \CoreBundle\Repository\HiveRepository $repo */
        $repo = $this->getRepository('CoreBundle:Hive');

        $query = $repo->queryMeHives($this->getUser());

        $pagination = $this->getPagination($request, $query, 'hive');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'hives' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    /**
     * @return string
     */
    protected function getRepositoryName()
    {
        return '';
    }
}
