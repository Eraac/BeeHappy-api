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

        $helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
        $path = $helper->asset($user, 'image');

        return [
            'user' => $user,
            'image_link' => $path,
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
