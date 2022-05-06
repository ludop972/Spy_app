<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgentRepository::class)]
class Agent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $lastname;

    #[ORM\Column(type: 'string', length: 255)]
    private $firstname;

    #[ORM\Column(type: 'string', length: 255)]
    private $id_code;

    #[ORM\Column(type: 'datetime')]
    private $date_of_birth;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'agents')]
    #[ORM\JoinColumn(nullable: false)]
    private $nationality;

    #[ORM\ManyToMany(targetEntity: Specialities::class, inversedBy: 'agents')]
    private $specialities;

    #[ORM\ManyToMany(targetEntity: Mission::class, mappedBy: 'agents')]
    private $missions;

    public function __construct()
    {
        $this->specialities = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLastname();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getIdCode(): ?string
    {
        return $this->id_code;
    }

    public function setIdCode(string $id_code): self
    {
        $this->id_code = $id_code;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getNationality(): ?country
    {
        return $this->nationality;
    }

    public function setNationality(?country $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection<int, specialities>
     */
    public function getSpecialities(): Collection
    {
        return $this->specialities;
    }

    public function addSpeciality(specialities $speciality): self
    {
        if (!$this->specialities->contains($speciality)) {
            $this->specialities[] = $speciality;
        }

        return $this;
    }

    public function removeSpeciality(specialities $speciality): self
    {
        $this->specialities->removeElement($speciality);

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
            $mission->addAgent($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeAgent($this);
        }

        return $this;
    }

    public function displaySpecialities(): array
    {
        $speciality = $this->specialities;
        $specialitiesList = [];
        foreach($speciality as $s)
        {
            $specialitiesList[] = $s->getName();
        }
        return $specialitiesList;
    }
}
