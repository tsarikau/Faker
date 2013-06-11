<?php
 /*
  IsolatedPopulator.php
  User: tsarikau
  Date: 6/11/13
  Time: 1:41 PM
 */

namespace Faker\ORM\Doctrine;


class IsolatedPopulator extends Populator {
    /**
     * Populate the database using all the Entity classes previously added.
     *
     * @param EntityManager $entityManager A Propel connection object
     *
     * @return array A list of the inserted PKs
     */
    public function execute($entityManager = null)
    {
        if (null === $entityManager) {
            $entityManager = $this->manager;
        }
        if (null === $entityManager) {
            throw new \InvalidArgumentException("No entity manager passed to Doctrine Populator.");
        }

        $insertedEntities = array();
        foreach ($this->quantities as $class => $number) {
            $generateId = $this->generateId[$class];
            for ($i=0; $i < $number; $i++) {
                $insertedEntities[$class][]=$e= $this->entities[$class]->execute($entityManager, $insertedEntities, $generateId);
                $this->manager->detach($e);
            }
        }

        return $insertedEntities;
    }
}