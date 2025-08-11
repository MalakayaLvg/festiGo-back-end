<?php

namespace App\Entity;

use App\Repository\FestivalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[ORM\Entity(repositoryClass: FestivalRepository::class)]
class Festival
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['festival-detail','scene-detail','stand-detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['festival-detail','scene-detail','stand-detail'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['festival-detail'])]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups(['festival-detail'])]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Groups(['festival-detail'])]
    private ?string $place = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['festival-detail'])]
    private ?int $availablePlaces = null;

    #[ORM\Column]
    #[Groups(['festival-detail'])]
    private ?int $giftCredits = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['festival-detail'])]
    private ?int $visitorLive = null;

    #[ORM\Column]
    #[Groups(['festival-detail'])]
    private ?\DateTime $startDate = null;

    #[ORM\Column]
    #[Groups(['festival-detail'])]
    private ?\DateTime $endDate = null;

    /**
     * @var Collection<int, Scene>
     */
    #[ORM\OneToMany(targetEntity: Scene::class, mappedBy: 'festival', orphanRemoval: true)]
    #[Groups(['festival-detail'])]
    private Collection $scenes;

    /**
     * @var Collection<int, Stand>
     */
    #[ORM\OneToMany(targetEntity: Stand::class, mappedBy: 'festival', orphanRemoval: true)]
    #[Groups(['festival-detail'])]
    private Collection $stands;

    /**
     * @var Collection<int, Ticket>
     */
    #[ORM\OneToMany(targetEntity: Ticket::class, mappedBy: 'festival', orphanRemoval: true)]
    private Collection $tickets;

    public function __construct()
    {
        $this->scenes = new ArrayCollection();
        $this->stands = new ArrayCollection();
        $this->tickets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getAvailablePlaces(): ?int
    {
        return $this->availablePlaces;
    }

    public function setAvailablePlaces(?int $availablePlaces): static
    {
        $this->availablePlaces = $availablePlaces;

        return $this;
    }

    public function getGiftCredits(): ?int
    {
        return $this->giftCredits;
    }

    public function setGiftCredits(int $giftCredits): static
    {
        $this->giftCredits = $giftCredits;

        return $this;
    }

    public function getVisitorLive(): ?int
    {
        return $this->visitorLive;
    }

    public function setVisitorLive(?int $visitorLive): static
    {
        $this->visitorLive = $visitorLive;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, Scene>
     */
    public function getScenes(): Collection
    {
        return $this->scenes;
    }

    public function addScene(Scene $scene): static
    {
        if (!$this->scenes->contains($scene)) {
            $this->scenes->add($scene);
            $scene->setFestival($this);
        }

        return $this;
    }

    public function removeScene(Scene $scene): static
    {
        if ($this->scenes->removeElement($scene)) {
            // set the owning side to null (unless already changed)
            if ($scene->getFestival() === $this) {
                $scene->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Stand>
     */
    public function getStands(): Collection
    {
        return $this->stands;
    }

    public function addStand(Stand $stand): static
    {
        if (!$this->stands->contains($stand)) {
            $this->stands->add($stand);
            $stand->setFestival($this);
        }

        return $this;
    }

    public function removeStand(Stand $stand): static
    {
        if ($this->stands->removeElement($stand)) {
            // set the owning side to null (unless already changed)
            if ($stand->getFestival() === $this) {
                $stand->setFestival(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): static
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setFestival($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): static
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getFestival() === $this) {
                $ticket->setFestival(null);
            }
        }

        return $this;
    }
}
