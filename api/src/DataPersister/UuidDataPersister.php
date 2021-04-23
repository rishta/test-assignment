<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Core\DataPersister\ResumableDataPersisterInterface;
use App\Entity\User;
use App\Entity\PhoneBookEntry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Ramsey\Uuid\Uuid;

/**
 * Class: OwnerDataPersister
 *
 * @see ContextAwareDataPersisterInterface
 * @see ResumableDataPersisterInterface
 */
class UuidDataPersister implements ContextAwareDataPersisterInterface, ResumableDataPersisterInterface
{
    private $entityManager;
    private $userPasswordEncoder;

    /**
     * __construct
     *
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
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

        return $robj->hasMethod('setUuid') && !($data instanceof PhoneBookEntry);
    }

    /**
     * persist
     *
     * @param mixed $data
     * @param array $context
     */
    public function persist($data, array $context = [])
    {
        $data->setUuid(Uuid::Uuid4());

        try {
            $this->entityManager->persist($data);
            $this->entityManager->flush();
        } catch (\Doctrine\DBAL\DBALException $e) {
        }

        dd(get_class($data));

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
