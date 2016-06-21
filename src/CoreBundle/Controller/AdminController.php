<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Security\HiveVoter;
use CoreBundle\Form\Type\HiveType;

class AdminController extends CoreController
{
    /**
     * @View(serializerGroups={"Default"})
     */
    public function getAdminHivesAction(Request $request)
    {
        /** @var \CoreBundle\Filter\HiveFilter $filter */
        $filter = $this->getFilter('core.hive_filter', $request);

        $query = $filter->getQuery('queryBuilderHives');

        $pagination = $this->getPagination($request, $query, 'hive');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'hives' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    public function getAdminUsersAction(Request $request)
    {
        /** @var \CoreBundle\Filter\UserFilter $filter */
        $filter = $this->getFilter('core.user_filter', $request);

        $query = $filter->getQuery('queryBuilderAdminUsers');

        $pagination = $this->getPagination($request, $query, 'user');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'users' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    protected function getRepositoryName()
    {
        return 'CoreBundle:Hive';
    }
}
