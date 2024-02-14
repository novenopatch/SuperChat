<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Utils\DateUtilsInterface;
use DateTimeImmutable;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[Vich\Uploadable]
#[ORM\Table(name: '`user`')]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface,DateUtilsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profile = null;
    #[Vich\UploadableField(mapping:"user_profile", fileNameProperty:"profile")]
    #[Assert\Image(mimeTypes: ["image/jpeg", "image/jpg","image/png"], allowLandscape: true, allowPortrait: true,)]
    private $profileFile ;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable $updateAt;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\ManyToMany(targetEntity: DiscussionEntity::class, mappedBy: 'users')]
    private Collection $UsersDiscussions;

    #[ORM\OneToMany(targetEntity: MessageContent::class, mappedBy: 'sender')]
    private Collection $messageContents;

    #[ORM\Column]
    private ?bool $online = true;



    public function __construct()
    {
        $this->message = new ArrayCollection();
        $this->UsersDiscussions = new ArrayCollection();
        $this->messageContents = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    public function __toString(): string
    {
        return $this->username ?? $this->id;
    }
    /**
     * @return File|null
     */
    public function getProfileFile(): ?File
    {
        return $this->profileFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $profileFile
     */
    public function setProfileFile(?File $profileFile= null):void
    {
        $this->profileFile = $profileFile;

        if (null !== $profileFile) {
            $this->updateAt = new \DateTimeImmutable('now');
        }
        //$this->imageFile = null;
    }
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    public function setProfile(?string $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCreateAt(): ?DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(DateTimeImmutable $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getUpdateAt(): ?DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    #[ORM\PrePersist]
    public function OnInitialSave()
    {
        $this->createAt = new DateTimeImmutable('now');
    }

    #[ORM\PreUpdate]
    public function OnUpdate()
    {
        $this->updateAt = new DateTimeImmutable('now');
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, DiscussionEntity>
     */
    public function getUsersDiscussions(): Collection
    {
        return $this->UsersDiscussions;
    }

    public function addUsersDiscussion(DiscussionEntity $usersDiscussion): static
    {
        if (!$this->UsersDiscussions->contains($usersDiscussion)) {
            $this->UsersDiscussions->add($usersDiscussion);
            $usersDiscussion->addUser($this);
        }

        return $this;
    }

    public function removeUsersDiscussion(DiscussionEntity $usersDiscussion): static
    {
        if ($this->UsersDiscussions->removeElement($usersDiscussion)) {
            $usersDiscussion->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, MessageContent>
     */
    public function getMessageContents(): Collection
    {
        return $this->messageContents;
    }

    public function addMessageContent(MessageContent $messageContent): static
    {
        if (!$this->messageContents->contains($messageContent)) {
            $this->messageContents->add($messageContent);
            $messageContent->setSender($this);
        }

        return $this;
    }

    public function removeMessageContent(MessageContent $messageContent): static
    {
        if ($this->messageContents->removeElement($messageContent)) {
            // set the owning side to null (unless already changed)
            if ($messageContent->getSender() === $this) {
                $messageContent->setSender(null);
            }
        }

        return $this;
    }

    public function isOnline(): ?bool
    {
        return $this->online;
    }

    public function setOnline(bool $online): static
    {
        $this->online = $online;

        return $this;
    }


}
