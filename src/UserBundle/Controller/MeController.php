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
        $user = $this->getUser();

        return [
            'user' => $user,
            'image_link' => $this->getPathPicture($user),
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
