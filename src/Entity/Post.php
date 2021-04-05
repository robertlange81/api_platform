<?php
  
  declare(strict_types=1);
  
  namespace App\Entity;
  
  use ApiPlatform\Core\Annotation\ApiFilter;
  use ApiPlatform\Core\Annotation\ApiResource;
  use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
  use App\Validator\IsValidOwner;
  use DateTime;
  use Doctrine\ORM\Mapping as ORM;
  use Exception;
  use Ramsey\Uuid\Uuid;
  use Ramsey\Uuid\UuidInterface;
  use Symfony\Component\Serializer\Annotation\Groups;
  use Symfony\Component\Validator\Constraints as Assert;

  /**
   * @ApiResource(
   *     collectionOperations={
   *              "get",
   *              "post"={"security"="is_granted('ROLE_USER')"}
   *     },
   *     itemOperations={
   *          "get"={
   *              "normalization_context"={"groups"={"post:read", "post:item:get"}},
   *          },
   *          "put"={
   *              "security"="is_granted('ROBERT_PUT_EDIT', object)",
   *              "security_message"="Only the creator can edit a post"
   *          },
   *          "delete"={"security"="is_granted('ROLE_ADMIN')"}
   *     },
   *     shortName="posts",
   *     normalizationContext={"groups"={"post:read"}, "swagger_definition_name"="Read"},
   *     denormalizationContext={"groups"={"post:write"}, "swagger_definition_name"="Write"},
   *     attributes={
   *          "pagination_items_per_page"=10,
   *          "formats"={"jsonld", "json", "html", "jsonhal", "csv"={"text/csv"}}
   *     }
   * )
   * @ApiFilter(DateFilter::class, properties={"created"})
 * @ORM\Entity
 * @ORM\Table(name="posts")
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({"App\Doctrine\PostSetOwnerListener"})
 */
class Post
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     *
     * @var UuidInterface
     */
    private $id;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     * @IsValidOwner()
     */
    private $owner;
    
    /**
     * @ORM\Column(name="message", type="string")
     * @Groups({"post:read", "post:write", "user:read", "user:write"})
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50,
     *     maxMessage="Describe your item in 50 chars or less"
     * )
     * @var string
     */
    private $message;
    
    /**
     * @ORM\Column(name="created", type="datetime")
     *
     * @var DateTime
     */
    private $created;
    
    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     *
     * @var DateTime|null
     */
    private $updated;
    
    /**
     * @ORM\PrePersist
     *
     * @throws Exception;
     */
    public function onPrePersist(): void
    {
        $this->id = Uuid::uuid4();
        $this->created = new DateTime('NOW');
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate(): void
    {
        $this->updated = new DateTime('NOW');
    }
    
    public function getId(): UuidInterface
    {
        return $this->id;
    }
    
    public function getMessage(): string
    {
        return $this->message;
    }
    
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }
    
    public function getCreated(): DateTime
    {
        return $this->created;
    }
    
    public function getUpdated(): ?DateTime
    {
        return $this->updated;
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
