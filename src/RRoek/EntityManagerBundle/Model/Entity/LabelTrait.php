<?php

namespace RRoek\EntityManagerBundle\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait LabelTrait.
 */
trait LabelTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return mixed
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }
}
