<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\PhoneBookEntryRepository;
use Doctrine\ORM\Mapping as ORM;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object.owner == user"},
 *     }
 * )
 * @ORM\Entity(repositoryClass=PhoneBookEntryRepository::class)
 */
class PhoneBookEntry
{
    /**
     * @var int
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var UuidInterface
     * @ApiProperty(identifier=true)
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     *
     * @var User
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="phone_number")
     *
     * @var PhoneNumber
     */
    private $number;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * getUuid
     *
     */
    public function getUuid():UuidInterface
    {
        return $this->uuid;
    }

    /**
     * setUuid
     *
     * @param mixed $uuid
     */
    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * getName
     *
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * setName
     *
     * @param string $name
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * getNumber
     *
     */
    public function getNumber():PhoneNumber
    {
        return $this->number;
    }

    /**
     * setNumber
     *
     * @param string|PhoneNumber $number
     */
    public function setNumber($number): self
    {
        if (is_string($number)) {
            $phoneNumberUtil = PhoneNumberUtil::getInstance();
            $number = $phoneNumberUtil->parse($number, null);
        }
        else
        {
            if (!($number instanceof PhoneNumber)) {
                throw new \InvalidArgumentException("Not a phone number");
            }
        }

        $this->number = $number;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
