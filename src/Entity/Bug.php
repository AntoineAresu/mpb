<?php

namespace App\Entity;

use App\Repository\BugRepository;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Table(name: 'bug_report')]
#[ORM\Entity(repositoryClass: BugRepository::class)]
class Bug extends UserRequest
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $title = '';

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private ?string $content = '';

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'bugs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /** @var Collection<int, Comment> */
    #[ORM\OneToMany(mappedBy: 'bug', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    /** @var Collection<int, Attachment> */
    #[ORM\OneToMany(mappedBy: 'bug', targetEntity: Attachment::class, orphanRemoval: true)]
    protected Collection $attachments;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $accountId = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $itemId = null;

    #[ORM\ManyToOne(targetEntity: Application::class, inversedBy: 'bugs')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotNull]
    private ?Application $application = null;

    #[ORM\ManyToOne(targetEntity: UserKind::class, inversedBy: 'bugs')]
    #[ORM\JoinColumn(nullable: true)]
    private ?UserKind $userKind = null;

    #[ORM\Column(type: 'boolean')]
    private bool $done = false;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'getBugsAssignedToMe')]
    private ?User $assignee = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachementName = null;

    /** @var Collection<int, Vote> */
    #[ORM\OneToMany(mappedBy: 'bug', targetEntity: Vote::class)]
    private Collection $votes;

    /** @var Collection<int, Tag> */
    #[ORM\ManyToMany(targetEntity: Tag::class, mappedBy: 'bugs')]
    private Collection $tags;

    public function __construct(#[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $userAgent = null)
    {
        $this->comments = new ArrayCollection();
        $this->attachments = new ArrayCollection();
        $this->votes = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBug($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBug() === $this) {
                $comment->setBug(null);
            }
        }

        return $this;
    }

    public function hasComments(): bool
    {
        return $this->comments->count() > 0;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(int $accountId): self
    {
        $this->accountId = $accountId;

        return $this;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(?int $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function setUserAgent(?string $userAgent): self
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function isDone(): ?bool
    {
        return $this->done;
    }

    public function setDone(bool $done): self
    {
        $this->done = $done;

        return $this;
    }

    public function getAssignee(): ?User
    {
        return $this->assignee;
    }

    public function setAssignee(?User $assignee): self
    {
        $this->assignee = $assignee;

        return $this;
    }

    public function getUserKind(): ?UserKind
    {
        return $this->userKind;
    }

    public function setUserKind(?UserKind $userKind): self
    {
        $this->userKind = $userKind;

        return $this;
    }

    public function getAttachementName(): ?string
    {
        return $this->attachementName;
    }

    public function setAttachementName(?string $attachementName): self
    {
        $this->attachementName = $attachementName;

        return $this;
    }

    /** @return Collection<int, Vote> */
    public function getVotes(): Collection
    {
        return $this->votes;
    }

    public function addVote(Vote $vote): self
    {
        if (!$this->votes->contains($vote)) {
            $this->votes->add($vote);
            $vote->setBug($this);
        }

        return $this;
    }

    public function removeVote(Vote $vote): self
    {
        if ($this->votes->removeElement($vote)) {
            // set the owning side to null (unless already changed)
            if ($vote->getBug() === $this) {
                $vote->setBug(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
            $tag->addBug($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeBug($this);
        }

        return $this;
    }

    public function resolve(): self
    {
        $this->done = true;

        return $this;
    }
}
