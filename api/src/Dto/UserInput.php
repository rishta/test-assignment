<?php
// src/Dto/UserInput.php

namespace App\Dto;

use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

final class UserInput {
    /**
     * @Groups({"user:write"})
     */
    public $password;

    /**
     * @Groups({"user:write"})
     */
    public $username;

    /**
     * @Groups({"user:write"})
     */
    public $email;
}
