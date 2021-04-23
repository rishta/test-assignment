<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiProperty;
use App\Repository\PhoneBookEntryShareRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"read"}},
 *     denormalizationContext={"groups"={"write"}},
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     collectionOperations={
 *         "get",
 *         "post"={"security"="is_granted('ROLE_ADMIN')"}
 *     },
 *     itemOperations={
 *         "get"={"security"="is_granted('ROLE_ADMIN') or user in [object.entry.owner, object.share]"},
 *         "post"={"security"="is_granted('ROLE_ADMIN') or object.entry.owner == user"},
 *         "put"={"security"="is_granted('ROLE_ADMIN') or object.entry.owner == user"},
 *         "delete"={"security"="is_granted('ROLE_ADMIN') or user in [object.entry.owner, object.share]"}
 *     }
 * )
 * @ORM\Entity(repositoryClass=PhoneBookEntryShareRepository::class)
 */
class PhoneBookEntryShare
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
     * @Groups({"read"})
     *
     * @ORM\Column(type="uuid", unique=true)
     */
    private $uuid;

    /**
     * @var User
     * @Groups({"read","write"})
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sharedPhones")
     * @ORM\JoinColumn(nullable=false)
     */
    private $share;

    /**
     * @Groups({"read","write"})
     * @Assert\Expression(
     *     "this.share !== value.owner",
     *     message="Don't bogart that phone, my friend!"
     * )
     *
     * @ORM\ManyToOne(targetEntity=PhoneBookEntry::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $entry;

    /**
     * @ORM\Column(type="boolean")
     */
    private $writable = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntry(): ?PhoneBookEntry
    {
        return $this->entry;
    }

    public function setEntry(?PhoneBookEntry $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function getShare(): ?User
    {
        return $this->share;
    }

    public function setShare(?User $share): self
    {
        $this->share = $share;

        return $this;
    }

    public function getWritable(): ?bool
    {
        return $this->writable;
    }

    public function setWritable(bool $writable): self
    {
        $this->writable = $writable;

        return $this;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function setUuid(UuidInterface $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }
}
