<?php

namespace App\Entity;

use App\Repository\DiscussionEntityRepository;
use App\Utils\DateUtilsInterface;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DiscussionEntityRepository::class)]
class DiscussionEntity implements DateUtilsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'UsersDiscussions')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: MessageContent::class, mappedBy: 'discussionEntity')]
    private Collection $messages;

    #[ORM\Column]
    private ?bool $groupDiscussion = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupThumbnail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupTitle = null;
    #[Vich\UploadableField(mapping:"group_thumbnail", fileNameProperty:"groupThumbnail")]
    #[Assert\Image(mimeTypes: ["image/jpeg", "image/jpg","image/png"], allowLandscape: true, allowPortrait: true,)]
    private $thumbnailFile ;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable $updateAt;
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        $this->users->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, MessageContent>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(MessageContent $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setDiscussionEntity($this);
        }

        return $this;
    }

    public function removeMessage(MessageContent $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDiscussionEntity() === $this) {
                $message->setDiscussionEntity(null);
            }
        }

        return $this;
    }

    public function isGroupDiscussion(): ?bool
    {
        return $this->groupDiscussion;
    }

    public function setGroupDiscussion(bool $groupDiscussion): static
    {
        $this->groupDiscussion = $groupDiscussion;

        return $this;
    }

    public function getGroupThumbnail(): ?string
    {
        return $this->groupThumbnail;
    }

    public function setGroupThumbnail(?string $groupThumbnail): static
    {
        $this->groupThumbnail = $groupThumbnail;

        return $this;
    }

    public function getGroupTitle(): ?string
    {
        return $this->groupTitle;
    }

    public function setGroupTitle(?string $groupTitle): static
    {
        $this->groupTitle = $groupTitle;

        return $this;
    }
    /**
     * @return File|null
     */
    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $ThumbnailFile
     */
    public function setThumbnailFile(?File $ThumbnailFile= null):void
    {
        $this->thumbnailFile = $ThumbnailFile;

        if (null !== $ThumbnailFile) {
            $this->updateAt = new \DateTimeImmutable('now');
        }
        //$this->imageFile = null;
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

}
