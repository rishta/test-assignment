<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ResumableDataPersisterInterface;
use App\Entity\User;
use App\Entity\PhoneBookEntry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Ramsey\Uuid\Uuid;

/**
 * Class: OwnerDataPersister
 *
 * @see ContextAwareDataPersisterInterface
 * @see ResumableDataPersisterInterface
 */
class OwnerDataPersister implements ContextAwareDataPersisterInterface, ResumableDataPersisterInterface
{
    private $decorated;
    private $entityManager;
    private $security;

    /**
     * __construct
     *
     * @param EntityManagerInterface $entityManager
     * @param Security $security
     */
    public function __construct(ContextAwareDataPersisterInterface $decorated, EntityManagerInterface $entityManager, Security $security)
    {
        $this->decorated = $decorated;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    /**
     * supports
     *
     * @param mixed $data
     * @param array $context
     */
    public function supports($data, array $context = []): bool
    {
        $robj = new \ReflectionObject($data);

        return $robj->hasMethod('setOwner') && ($data instanceof PhoneBookEntry);
    }

    /**
     * @param User $data
     */
    public function persist($data, array $context = [])
    {
        $data->setUuid(Uuid::Uuid4());
        if (empty($data->getOwner())) {
            $data->setOwner($this->security->getUser());
        }

        try {
            $this->decorated->persist($data);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
        }

        return $data;
    }

    /**
     * remove
     *
     * @param mixed $data
     * @param array $context
     */
    public function remove($data, array $context = [])
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }

    /**
     * resumable
     *
     * @param array $context
     */
    public function resumable(array $context = []): bool
    {
        return true;
    }
}
