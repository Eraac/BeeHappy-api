<?php

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/**
 * Class LocalizableTrait
 * @package CoreBundle\Entity\Traits
 *
 * @JMS\ExclusionPolicy("all")
 */
Trait LocalizableTrait
{
    /**
     * @var float $latitude
     *
     * @ORM\Column(type="decimal", precision=12, scale=10, nullable=true)
     * @Assert\Type(
     *     type="float",
     *     message="constraints.type"
     * )
     * @Assert\Range(
     *      min = -90,
     *      max = 90,
     *      minMessage = "constraints.range.min",
     *      maxMessage = "constraints.range.max",
     * )
     */
    private $latitude;

    /**
     * @var float $longitude
     *
     * @ORM\Column(type="decimal", precision=13, scale=10, nullable=true)
     * @Assert\Type(
     *     type="float",
     *     message="constraints.type"
     * )
     * @Assert\Range(
     *      min = -180,
     *      max = 180,
     *      minMessage = "constraints.range.min",
     *      maxMessage = "constraints.range.max",
     * )
     */
    private $longitude;


    /**
     * @JMS\VirtualProperty()
     */
    public function coordinate()
    {
        return [
            'lng' => $this->longitude,
            'lat' => $this->latitude,
        ];
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     *
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     *
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }
}
