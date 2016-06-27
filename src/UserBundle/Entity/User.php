<?php

namespace UserBundle\Entity;

use CoreBundle\Entity\Traits\UploadableTrait;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as JMS;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @Vich\Uploadable
 * @JMS\ExclusionPolicy("all")
 * @UniqueEntity(
 *     fields={"username"},
 *     message="constraints.unique",
 * )
 * @UniqueEntity(
 *     fields={"email"},
 *     message="constraints.unique",
 * )
 */
class User extends BaseUser
{
    use UploadableTrait;
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
