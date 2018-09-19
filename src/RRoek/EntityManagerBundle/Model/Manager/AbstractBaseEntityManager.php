<?php

namespace RRoek\EntityManagerBundle\Model\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use RRoek\EntityManagerBundle\Model\Manager\EntityManagerInterface as PersonalEntityManagerInterface;
use RRoek\EntityManagerBundle\Util\ConstraintViolationUtil;
use RRoek\EntityManagerBundle\Util\ValorizedEntityArrayTrait;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * Class AbstractBaseEntityManager.
 */
abstract class AbstractBaseEntityManager implements BaseEntityManagerInterface, PersonalEntityManagerInterface
{
    //---- --- Private & Protected Properties : --- ----
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var string */
    private $entityClass;

    /** @var ValidatorInterface */
    private $validatorService;

    //---- --- Used Traits : --- ----

    use ValorizedEntityArrayTrait;

    //---- --- Constructors : --- ----

    /**
     * AbstractBaseEntityManager constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validatorService
     * @param string $entityClass
     */
    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validatorService, string $entityClass)
    {
        $this->entityManager        = $this->refreshEntityManager($entityManager);
        $this->validatorService     = $validatorService;
        $this->entityClass          = $entityClass;
    }

    //---- --- Getters & Setters : --- ----
    /**
     * @return ValidatorInterface
     */
    public function getValidatorService()
    {
        return $this->validatorService;
    }

    /**
     * @param ValidatorInterface $validatorService
     */
    public function setValidatorService(ValidatorInterface $validatorService)
    {
        $this->validatorService = $validatorService;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * Get a new (dynamic) entity object.
     *
     * @return mixed
     */
    public function getNewEntityClass()
    {
        return new $this->entityClass();
    }

    //---- --- Private & Protected Methods : --- ----
    /**
     * Fully Reload EM if EM is not open anymore.
     *
     * @param mixed $entityManager
     *
     * @return EntityManager
     */
    private function refreshEntityManager($entityManager)
    {
        if (!$entityManager->isOpen()) {
            $entityManager = $entityManager::create(
                $entityManager->getConnection(),
                $entityManager->getConfiguration()
            );
        }

        return $entityManager;
    }

    /**
     * @throws EntityNotFoundException
     *
     * @return null
     */
    protected function createNotFoundException()
    {
        throw new EntityNotFoundException();
    }


    //---- --- Public Methods : --- ----
    /**
     * Get caller class name without namespace.
     *
     * @return string
     */
    public static function getClassName()
    {
        $classNameArray = explode('\\', get_called_class());

        return array_pop($classNameArray);
    }

    /**
     * Get the global Doctrine EM, use it carefully.
     *
     * @return EntityManagerInterface object manager
     */
    public function getEntityManager()
    {
        $this->entityManager = $this->refreshEntityManager($this->entityManager);

        return $this->entityManager;
    }

    /**
     * Return dynamically the entity repository whithout needing to specify class.
     *
     * @return ObjectRepository Related repository
     */
    public function getRepository()
    {
        return $this->getEntityManager()->getRepository($this->getEntityClass());
    }

    /**
     * Returns entity-item with given id.
     *
     * @param int $id
     *
     * @return object
     */
    public function read($id)
    {
        return $this->getEntityManager()->find($this->getEntityClass(), $id);
    }

    /**
     * Returns all entity-items.
     *
     * @return object[]
     */
    public function readAll()
    {
        return $this->getEntityManager()->getRepository($this->getEntityClass())->findAll();
    }

    /**
     * Creates a new item and set the data which are passed.
     *
     * @param array $data
     * @param bool  $persist
     * @param bool  $flush
     *
     * @return object
     */
    public function create(array $data, $persist = true, $flush = false)
    {
        //Create new entity & valid fieds format :
        $entity                  = $this->bind($this->getNewEntityClass(), $data);
        $constraintViolationList = $this->getValidatorService()->validate($entity);

        //Check allright :
        if (count($constraintViolationList) > 0) {
            return ConstraintViolationUtil::throwConstraintViolationListException($constraintViolationList);
        }
        //Persist & flush entity according to method parameters :
        if (true === $persist) {
            $this->persist($entity, $flush);
        }

        return $entity;
    }

    /**
     * Update the entity-item with given id.
     *
     * @param int $id
     * @param array $data
     * @param bool $flush
     * @param bool $validate
     *
     * @return object
     * @throws EntityNotFoundException
     */
    public function update($id, array $data, $flush = false, $validate = false)
    {
        $entity = $this->read($id);//Get entity object

        if (!$entity) {
            return $this->createNotFoundException();
        }

        //Update fields :
        $this->bind($entity, $data);

        if ($validate) {
            $validator               = $this->validatorService;
            $constraintViolationList = $validator->validate($entity);

            if (count($constraintViolationList) > 0) {
                return ConstraintViolationUtil::throwConstraintViolationListException($constraintViolationList);
            }
        }

        //According to method parameter flush entity (no need to persist your object allready exist !) :
        if ($flush) {
            $this->flush();
        }

        return $entity;
    }

    /**
     * Delete the entity-item with given id.
     *
     * @param int  $id
     * @param bool $flush
     *
     * @return null
     *
     * @throws EntityNotFoundException
     */
    public function delete($id, $flush = false)
    {
        $entity = $this->read($id);

        if (!$entity) {
            return $this->createNotFoundException();
        }

        $this->getEntityManager()->remove($entity);

        //According to method parameter flush entity :
        if ($flush) {
            $this->flush();
        }

        return null;
    }

    /**
     * @param object $entity
     * @param bool   $flush
     */
    public function persist($entity, $flush = false)
    {
        $this->getEntityManager()->persist($entity);//Your entity object is on persistance layer

        //Need to flush on Db ?
        if ($flush) {
            $this->flush();
        }
    }

    /**
     * Flush.
     */
    public function flush()
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->getEntityManager()->getClassMetadata($this->getNewEntityClass());
    }

    /**
     * @return array
     */
    public function getFieldNames()
    {
        return $this->getClassMetadata()->getFieldNames();
    }

    /**
     * @return array
     */
    public function getAssociationNames()
    {
        return $this->getClassMetadata()->getAssociationNames();
    }
}
