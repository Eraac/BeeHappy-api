<?php

namespace CoreBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Util\Codes;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CoreBundle\Security\HiveVoter;
use CoreBundle\Event\MeasureSentEvent;
use CoreBundle\Form\Type\MeasureType;
use CoreBundle\Entity\Measure;
use CoreBundle\Entity\Hive;
use CoreBundle\Entity\Type;

class MeasureController extends CoreController
{
    /**
     * @ParamConverter("hive", options={"mapping": {"hive": "slug"}})
     * @ParamConverter("type", options={"mapping": {"type": "slug"}})
     */
    public function getHivesMeasuresAction(Hive $hive, Type $type, Request $request)
    {
        $this->isGranted(HiveVoter::VIEW, $hive);

        /** @var \CoreBundle\Filter\MeasureFilter $filter */
        $filter = $this->getFilter('core.measure_filter', $request);

        /** @var \CoreBundle\Repository\MeasureRepository $repo */
        $repo = $this->getRepository();

        $totalItems = $repo->countPerHiveAndType($hive, $type);

        $page = $this->getPage($request);
        $itemPerPage = $this->getItemPerPage('measure', $request);

        $measures = $filter->getResult('queryBuilderMeasureByHiveAndType', [$hive, $type, $page, $itemPerPage]);

        return [
            'measures' => $measures,
            'total_items' => $totalItems,
            'item_per_page' => $itemPerPage,
            'page' => $page,
        ];
    }

    /**
     * @View(serializerGroups={"Default"}, statusCode=201)
     */
    public function postMeasureAction(Request $request)
    {
        $measure = new Measure();

        $apiKey = $request->query->get('api_key');
        $hive = $this->getHiveByApiKey($apiKey);
        $measure->setHive($hive);

        $response = $this->formMeasure($measure, $request, "post");

        return $response;
    }

    private function formMeasure(Measure $measure, Request $request, $method = 'post')
    {
        $form = $this->createForm(MeasureType::class, $measure, ['method' => $method]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($measure);
            $em->flush();

            $this->dispatch(MeasureSentEvent::NAME, new MeasureSentEvent($measure));

            return [
                'measure' => $measure,
            ];
        }

        return new JsonResponse($this->getAllErrors($form), Codes::HTTP_BAD_REQUEST);
    }

    /**
     * @param $apiKey
     *
     * @return \CoreBundle\Entity\Hive
     */
    private function getHiveByApiKey($apiKey)
    {
        /** @var \CoreBundle\Repository\HiveRepository $repo */
        $repo = $this->getRepository('CoreBundle:Hive');
        $hive = $repo->findByApiKey($apiKey);

        if (is_null($hive)) {
            throw $this->createNotFoundException($this->t('core.error.hive_not_found'));
        }

        return $hive;
    }

    /**
    * @return string
    */
    protected function getRepositoryName()
    {
        return 'CoreBundle:Measure';
    }
}
