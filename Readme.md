###Features

- Give you simple interfaces & abstraction to build yours Symfony's Entity Managers


# EntityManagerBundle

**Table of Contents**

[TOCM]

[TOC]

##Introduction
This small bundle give you the ability to build generic Entity Managers for you Symfony Application (2.7 ~ .3.4).
The goal is to make managers for all of your entities like [Sensio Symfony Best Practices](http://https://symfony.com/doc/current/best_practices/index.html "Sension Symfony Best Practices")  recommend it.

###Advantages
Make a manager for an entity permit to lighten you Controller. All the scope (CRUD Create Read Update Delete) of your entity will be managed behind your Entity Manager. 

This is useful for big projects or even small/medium projects. Every creation will call the manager like every select, update or delete.

##Goal
It's frequent that a team want to make entity managers, but have some technical difficulties.
This bundle give you an abstraction to make all your Entity Managers to the same format and already with CRUD methods.

##Use
###Activate bundle
To use it, simply `composer require rroek/entity-manager-bundle`
and enable it :
in AppKernel.php :

     /**
         * @return array
         */
        public function registerBundles()
        {
            $bundles = [
    		[...]
    		new Rroek\EntityManagerBundle\RroekEntityManagerBundle(),
    		[...]

###Make your Entity Manager
For this example, we will take "MyPersonalEntity" class wich is an *Doctrine Entity* in our Symfony Project. Its class repository is "MyPersonalEntityRepository". Waw you didn't expect it. No ?

Our Entity will have an id, a label, a relation with another entity & getters/setters for it.

#### So let's see interresting things :

I Want to have a manager who dispatch & make job all around my entity. All the CRUD.
So lets create our own manager class :

------------

In : 


    MyBundle
    	Entity
    		MyPersonalEntity.php
    		...
    	Manager
    		MyPersonalEntityManager.php
    	Repository
    		MyPersonalEntityRepository.php
    		...


------------


    <?php
    
    namespace Acme\MyBundle\Manager;
    
    use Rroek\EntityManagerBundle\Model\Manager\AbstractBaseEntityManager;
    use Rroek\EntityManagerBundle\Model\Manager\EntityManagerInterface as PersonalEntityManagerInterface;
    use Doctrine\ORM\EntityManagerInterface;
    use Marqueo\ServiceProductMyPersonalEntityManagerBundle\Entity\MyPersonalEntity;
    
    /**
     * Class MyPersonalEntityManager.
     */
    class MyPersonalEntityManager extends AbstractBaseEntityManager implements PersonalEntityManagerInterface
    {
        /**
         * MyPersonalEntityManager constructor.
         *
         * @param EntityManagerInterface $entityManager
         */
        public function __construct(EntityManagerInterface $entityManager)
        {
            parent::__construct($entityManager);
            $this->setEntityClass(MyPersonalEntity::class);//Use your Entity ClassName
            $this->setEntityClassNamespace('Acme\MyBundle\Entity\MyPersonalEntityManager');//Use Namespace of your Entity
        }
    
        /**
         * Bind data array to the given entity.
         *
         * @param MyPersonalEntityManager $entity
         * @param array $data
         *
         * @return MyPersonalEntityManager
         */
        protected function _bind(MyPersonalEntity $entity, array $data)
        {
			/*this function get an existing instance of our Entity, or a new instance (see create & update method on abstraction)
			All the data to set/update are stocked on $data array.
			*/
            $entity->setLabel($this->_getValue($data, 'label'));//We call the entity property setter and give "label" key of $data as value
            $entity->setLinkToAnotherEntity($this->_getValue($data, 'anotherEntity', null));//Here the same but for a joined Entity like ManyToOne or OneToOne (if you set no data for key 'anotherEntity' '' will be placed instead so for a join precise null to default value)
    
            return $entity;
        }
    }
    

------------

    services:
    [...]
    # ------ ------ ------ ------ ------
    # ENTITY MANAGERS SERVICES
    # ------ ------ ------ ------ ------
    
        acme_my_bundle.my_personal_entity.entity.manager:
            class: 'Acme\MyBundle\Manager\MyPersonalEntityManager'
            arguments:
                - '@doctrine.orm.entity_manager'
            calls:
                - [setValidatorService, ['@validator']]
Declare for easy use, your manager as a Symfony service :

------------


And its all ! Your entity manager is created, it have allready CRUD methods :


    [...]
        /**
         * Returns entity-item with given id.
         *
         * @param int $id
         *
         * @return object
         */
        public function read($id){...}
    
        /**
         * Returns all entity-items.
         *
         * @return object[]
         */
        public function readAll(){...}
    
        /**
         * Creates a new item and set the data which are passed.
         *
         * @param array $data
         * @param bool  $persist
         * @param bool  $flush
         *
         * @return object
         */
        public function create(array $data, $persist = true, $flush = false){...}
    
        /**
         * Update the entity-item with given id.
         *
         * @param int   $id
         * @param array $data
         * @param bool  $flush
         * @param bool  $validate
         *
         * @return object
         */
        public function update($id, array $data, $flush = false, $validate = false){...}
    
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
        public function delete($id, $flush = false){...}

And some convenient methods :


    [...]
    
        /**
         * Return dynamically the entity repository whithout needing to specify class.
         *
         * @return ObjectRepository Related repository
         */
        public function getRepository(){...}
    
    
        /**
         * @param object $entity
         * @param bool   $flush
         */
        public function persist($entity, $flush = false){...}
    
    
        /**
         * Flush.
         */
        public function flush(){...}
    
    
        /**
         * @return \Doctrine\ORM\Mapping\ClassMetadata
         */
        public function getClassMetadata(){...}
    
        /**
         * @return array
         */
        public function getFieldNames(){...}
    
        /**
         * @return array
         */
        public function getAssociationNames(){...}
    
#### Caution
Be careful a manager only manage one and only one Entity (Entity with its repository).

##Bonus

You will find 2 generic Traits for your entities creation.
IdTrait and LabelTrait.
To use it :


    [...]
    use Rroek\EntityManagerBundle\Model\Entity\IdTrait;
    use Rroek\EntityManagerBundle\Model\Entity\LabelTrait;
    
    [...]
it permit to use same declaration of id or label. 
Content :


    
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

------------


And :

------------



    
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
##Enjoy !