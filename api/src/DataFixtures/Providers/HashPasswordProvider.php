<?php

namespace App\DataFixtures\Providers;

use App\Entity\User;
use Faker\Provider\Base as BaseProvider;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HashPasswordProvider extends BaseProvider
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function hashPassword(string $plainPassword): string
    {
        $pass = $this->encoder->encodePassword(new User(), $plainPassword);
        return $pass;
    }
}

