<?php

namespace RRoek\EntityManagerBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait IdTrait.
 */
trait IdTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", precision=0, scale=0, nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * JMS\Expose
     */
    private $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return object
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}
