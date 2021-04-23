<?php

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Entity\User;
use App\Dto\UserInput;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class: UserInputDataTransformer
 *
 * @see DataTransformerInterface
 * @final
 */
final class UserInputDataTransformer implements DataTransformerInterface
{
    private $userPasswordEncoder;

    /**
     * __construct
     *
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($input, string $to, array $context = [])
    {
        $user = new $to();

        throw new \Exception(print_r($input, true));

        $user->setPassword(
            $this->userPasswordEncoder->encodePassword($user, $input->password)
        );

        $user->setUsername($input->username);
        $user->setEmail($input->email);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        // in the case of an input, the value given here is an array (the JSON decoded).
        // if it's a user we transformed the data already
        if ($data instanceof User) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
