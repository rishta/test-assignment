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
        $robj = new \ReflectionObject($data);

        if (!$robj->hasMethod('setUuid')) {
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

