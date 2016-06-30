<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Entity\Hive;
use CoreBundle\Security\HiveVoter;
use CoreBundle\Form\Type\HiveType;
use CoreBundle\Form\Type\HivePictureType;

class HiveController extends CoreController
{
    /**
     * @View(serializerGroups={"Default"})
     */
    public function getHivesAction(Request $request)
    {
        /** @var \CoreBundle\Filter\HiveFilter $filter */
        $filter = $this->getFilter('core.hive_filter', $request);

        $query = $filter->getQuery('queryBuilderHivesByUser', [$this->getUser()]);

        $pagination = $this->getPagination($request, $query, 'hive');

        return [
            'total_items' => $pagination->getTotalItemCount(),
            'item_per_page' => $pagination->getItemNumberPerPage(),
            'hives' => $pagination->getItems(),
            'page' => $pagination->getCurrentPageNumber(),
        ];
    }

    /**
     * @View(serializerGroups={"Default", "detail-hive", "me-hive"})
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function getHiveAction(Hive $hive, Request $request)
    {
        $this->isGranted(HiveVoter::VIEW, $hive);

        return [
            'hive' => $hive,
            'image_link' => $this->getPathPicture($hive),
        ];
    }

    /**
     * @View(serializerGroups={"Default", "detail-hive", "me-hive"}, statusCode=201)
     */
    public function postHiveAction(Request $request)
    {
        $this->isGranted(HiveVoter::CREATE, $hive = new Hive());

        $hive->setOwner($this->getUser());

        return $this->formHive($hive, $request, "post");
    }

    /**
     * @View(serializerGroups={"Default", "detail-hive", "me-hive"})
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function patchHiveAction(Hive $hive, Request $request) // use POST because no file uploaded on PATCH
    {
        $this->isGranted(HiveVoter::EDIT, $hive);

        return $this->formHive($hive, $request, 'patch');
    }

    /**
     * @View(serializerGroups={"Default", "detail-hive", "me-hive"})
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function postHivePictureAction(Hive $hive, Request $request)
    {
        $this->isGranted(HiveVoter::EDIT, $hive);

        return $this->formHive($hive, $request, 'post', true);
    }

    /**
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     */
    public function deleteHiveAction(Hive $hive)
    {
        $this->isGranted(HiveVoter::DELETE, $hive);

        $this->getManager()->remove($hive);
        $this->getManager()->flush();
    }

    private function formHive(Hive $hive, Request $request, $method = 'post', $onlyPicture = false)
    {
        $formType = $onlyPicture ? HivePictureType::class : HiveType::class;

        $form = $this->createForm($formType, $hive, ['method' => $method]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getManager();
            $em->persist($hive);
            $em->flush();

            return [
                'hive' => $hive,
                'image_link' => $this->getPathPicture($hive),
            ];
        }

        return new JsonResponse($this->getAllErrors($form), Codes::HTTP_BAD_REQUEST);
    }

    protected function getRepositoryName()
    {
        return 'CoreBundle:Hive';
    }
}
