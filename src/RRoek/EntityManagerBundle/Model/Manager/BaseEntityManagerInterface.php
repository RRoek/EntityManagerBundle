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
    public function getEntityClass();

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass);

    /**
     * @return mixed
     */
    public function getEntityClassNamespace();

    /**
     * @param string $entityClassNamespace
     */
    public function setEntityClassNamespace($entityClassNamespace);

    /**
     * @return EntityManagerInterface object manager
     */
    public function getEntityManager();

    /**
     * @return ValidatorInterface
     */
    public function getValidatorService();

    /**
     * @param mixed $validatorService
     */
    public function setValidatorService(ValidatorInterface $validatorService);

    /**
     * @return ObjectRepository related repository
     */
    public function getRepository();
}
