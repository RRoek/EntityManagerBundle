<?php

namespace RRoek\EntityManagerBundle\Model\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Interface EntityManagerInterface.
 */
interface EntityManagerInterface
{
    /**
     * EntityManagerInterface constructor.
     *
     * @param DoctrineEntityManagerInterface $entityManager
     * @param ValidatorInterface $validatorService
     * @param string $entityClass
     */
    public function __construct(DoctrineEntityManagerInterface $entityManager, ValidatorInterface $validatorService, string $entityClass);

    /**
     * Returns entity-item with given id.
     *
     * @param int $id
     *
     * @return object
     */
    public function read($id);

    /**
     * Returns all entity-items.
     *
     * @return object[]
     */
    public function readAll();

    /**
     * Creates a new entity-item and set the data which are passed.
     *
     * @param array $data    mandatory: title; optional: teaser, description
     * @param bool  $persist
     * @param bool  $flush
     *
     * @return object
     */
    public function create(array $data, $persist = false, $flush = false);

    /**
     * Update the entity-item with given id.
     *
     * @param int   $id
     * @param array $data
     *
     * @return object
     */
    public function update($id, array $data);

    /**
     * Delete the entity-item with given id.
     *
     * @param int  $id
     * @param bool $flush
     */
    public function delete($id, $flush = false);

    /**
     * @param object $entity
     * @param bool   $flush
     */
    public function persist($entity, $flush = false);

    /**
     * @return mixed
     */
    public function flush();

    /**
     * @return mixed
     */
    public function getEntityClass();

    /**
     * @return ObjectRepository
     */
    public function getRepository();
}
