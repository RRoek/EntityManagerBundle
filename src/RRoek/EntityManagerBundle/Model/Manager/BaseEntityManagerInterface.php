<?php

namespace RRoek\EntityManagerBundle\Model\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface BaseEntityManagerInterface.
 */
interface BaseEntityManagerInterface
{
    /**
     * @return string
     */
    public function getEntityClassName();

    /**
     * @param string $entityClass
     */
    public function setEntityClassName($entityClass);

    /**
     * @return mixed
     */
    public function getNewEntityClass();

    /**
     * @return EntityManagerInterface object manager
     */
    public function getEntityManager();

    /**
     * @return ValidatorInterface
     */
    public function getValidatorService();

    /**
     * @return ObjectRepository related repository
     */
    public function getRepository();
}
