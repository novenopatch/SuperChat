<?php

namespace App\Entity;

use App\Repository\MessageContentRepository;
use App\Utils\DateUtilsInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageContentRepository::class)]
class MessageContent implements DateUtilsInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?DiscussionEntity $discussionEntity = null;

    #[ORM\ManyToOne(inversedBy: 'messageContents')]
    private ?User $sender = null;


    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createAt;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private DateTimeImmutable $updateAt;
    public function getId(): ?int
    {
        return $this->id;
    }



    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getDiscussionEntity(): ?DiscussionEntity
    {
        return $this->discussionEntity;
    }

    public function setDiscussionEntity(?DiscussionEntity $discussionEntity): static
    {
        $this->discussionEntity = $discussionEntity;

        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): static
    {
        $this->sender = $sender;

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

}
