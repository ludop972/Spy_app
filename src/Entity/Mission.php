<?php

namespace App\Entity;

use App\Repository\MissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MissionRepository::class)]
class Mission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'string', length: 255)]
    private $code_name;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private $country;

    #[ORM\ManyToMany(targetEntity: Agent::class, inversedBy: 'missions')]
    private $agents;

    #[ORM\ManyToMany(targetEntity: Contact::class, inversedBy: 'missions')]
    private $contacts;

    #[ORM\ManyToMany(targetEntity: Target::class, inversedBy: 'missions')]
    private $targets;

    #[ORM\ManyToMany(targetEntity: Hideouts::class, inversedBy: 'missions')]
    private $hideouts;

    #[ORM\ManyToOne(targetEntity: Specialities::class, inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private $specialities;

    #[ORM\Column(type: 'datetime')]
    private $start_date;

    #[ORM\Column(type: 'datetime')]
    private $end_date;

    #[ORM\ManyToOne(targetEntity: TypeMission::class, inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private $type_of_mission;

    #[ORM\ManyToOne(targetEntity: StatusMission::class, inversedBy: 'missions')]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    public function __toString() {
        return $this->getTitle();
    }

    public function __construct()
    {
        $this->agents = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->targets = new ArrayCollection();
        $this->hideouts = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCodeName(): ?string
    {
        return $this->code_name;
    }

    public function setCodeName(string $code_name): self
    {
        $this->code_name = $code_name;

        return $this;
    }

    public function getCountry(): ?country
    {
        return $this->country;
    }

    public function setCountry(?country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Collection<int, agent>
     */
    public function getAgents(): Collection
    {
        return $this->agents;
    }

    public function addAgent(agent $agent): self
    {
        if (!$this->agents->contains($agent)) {
            $this->agents[] = $agent;
        }

        return $this;
    }

    public function removeAgent(agent $agent): self
    {
        $this->agents->removeElement($agent);

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
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        $this->contacts->removeElement($contact);

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
        }

        return $this;
    }

    public function removeTarget(Target $target): self
    {
        $this->targets->removeElement($target);

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
        }

        return $this;
    }

    public function removeHideout(Hideouts $hideout): self
    {
        $this->hideouts->removeElement($hideout);

        return $this;
    }

    public function getSpecialities(): ?Specialities
    {
        return $this->specialities;
    }

    public function setSpecialities(?Specialities $specialities): self
    {
        $this->specialities = $specialities;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): self
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getTypeOfMission(): ?TypeMission
    {
        return $this->type_of_mission;
    }

    public function setTypeOfMission(?TypeMission $type_of_mission): self
    {
        $this->type_of_mission = $type_of_mission;

        return $this;
    }

    public function getStatus(): ?StatusMission
    {
        return $this->status;
    }

    public function setStatus(?StatusMission $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function contactsAreValid(): bool
    {
        $dataCountry = $this->country;
        $dataContacts = $this->contacts;

        foreach ($dataContacts as $contact) {
            if ($dataCountry != $contact->getNationality()) {
                return false;
            }
        }
        return true;
    }

    public function stashsIsValid(): bool
    {
        $dataCountry = $this->country;
        $dataHideouts = $this->hideouts;

        foreach ($dataHideouts as $hideout) {
            if ($hideout->getCountry() != $dataCountry) {
                return false;
            }
        }
        return true;
    }

    public function agentsSpecialitiesAreValid(): bool
    {
        $dataSpecialities = $this->specialities;
        $dataAgents = $this->agents;

        $validSpecialitiesAgents = 0;

        foreach ($dataAgents as $agent) {
            $agentSpecialities = $agent->displaySpecialities();
            if (in_array($dataSpecialities->getName(), $agentSpecialities)) {
                $validSpecialitiesAgents += 1;
            }
            if ($validSpecialitiesAgents == 0) {
                return false;
            }
        }
        return true;
    }

    public function agentsCountryAreValid(): bool
    {
        $dataAgents = $this->agents;
        $dataTargets = $this->targets;

        foreach ($dataAgents as $agent) {
            foreach ($dataTargets as $target) {
                if ($agent->getNationality() == $target->getNationality()) {
                    return false;
                }
            }
        }
        return true;
    }

    public function missionError(): bool
    {

        if (!$this->contactsAreValid() || !$this->stashsIsValid() || !$this->agentsSpecialitiesAreValid() || !$this->agentsCountryAreValid()) {
            return false;
        }
        return true;
    }


}
