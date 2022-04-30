<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\OneToMany(mappedBy: 'nationality', targetEntity: Agent::class)]
    private $agents;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Hideouts::class)]
    private $hideouts;

    #[ORM\OneToMany(mappedBy: 'nationality', targetEntity: Contact::class)]
    private $contacts;

    #[ORM\OneToMany(mappedBy: 'nationality', targetEntity: Target::class)]
    private $targets;

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Mission::class)]
    private $missions;

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->hideouts = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->targets = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(Agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents[] = $agent;
            $agent->setNationality($this);
        }

        return $this;
    }

    public function removeAgent(Agent $agent): self
    {
        if ($this->agents->removeElement($agent)) {
            // set the owning side to null (unless already changed)
            if ($agent->getNationality() === $this) {
                $agent->setNationality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hideouts>
     */
    public function getHideouts(): Collection
    {
        return $this->hideouts;
    }

    public function addHideout(Hideouts $hideout): self
    {
        if (!$this->hideouts->contains($hideout)) {
            $this->hideouts[] = $hideout;
            $hideout->setCountry($this);
        }

        return $this;
    }

    public function removeHideout(Hideouts $hideout): self
    {
        if ($this->hideouts->removeElement($hideout)) {
            // set the owning side to null (unless already changed)
            if ($hideout->getCountry() === $this) {
                $hideout->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Contact>
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setNationality($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getNationality() === $this) {
                $contact->setNationality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Target>
     */
    public function getTargets(): Collection
    {
        return $this->targets;
    }

    public function addTarget(Target $target): self
    {
        if (!$this->targets->contains($target)) {
            $this->targets[] = $target;
            $target->setNationality($this);
        }

        return $this;
    }

    public function removeTarget(Target $target): self
    {
        if ($this->targets->removeElement($target)) {
            // set the owning side to null (unless already changed)
            if ($target->getNationality() === $this) {
                $target->setNationality(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Mission>
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setCountry($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getCountry() === $this) {
                $mission->setCountry(null);
            }
        }

        return $this;
    }
}
