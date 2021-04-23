<?php

namespace App\DataFixtures\Processor;

use App\Entity\User;
use App\Entity\PhoneBookEntry;
use App\Entity\PhoneBookEntryShare;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UuidProcessor implements ProcessorInterface
{
    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @inheritdoc
     */
    public function preProcess(string $fixtureId, $data): void
    {
        /**
         * this should be refactored to use a reflection
         * which would check for a UUID property and methods
         */
        $leave = true;

        if ($data instanceof User) {
            $leave = false;
        }

        if ($data instanceof PhoneBookEntry) {
            $leave = false;
        }

        if ($data instanceof PhoneBookEntryShare) {
            $leave = false;
        }

        if ($leave) {
            return;
        }

        $data->setUuid(Uuid::Uuid4());
    }

    /**
     * @inheritdoc
     */
    public function postProcess(string $fixtureId, $object): void
    {
        // do nothing
    }
}

