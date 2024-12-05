<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $subjectId = null;

    #[ORM\Column(length: 255)]
    private ?string $subjectName = null;

    #[ORM\Column(length: 255)]
    private ?string $subjectCode = null;

    #[ORM\Column(length: 255)]
    private ?string $department = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, ClassEntity>
     */
    #[ORM\OneToMany(targetEntity: ClassEntity::class, mappedBy: 'subject')]
    private Collection $classEntities;

    /**
     * @var Collection<int, Session>
     */
    #[ORM\OneToMany(targetEntity: Session::class, mappedBy: 'subject')]
    private Collection $sessions;

    public function __construct()
    {
        $this->classEntities = new ArrayCollection();
        $this->sessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubjectId(): ?string
    {
        return $this->subjectId;
    }

    public function setSubjectId(string $subjectId): static
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    public function getSubjectName(): ?string
    {
        return $this->subjectName;
    }

    public function setSubjectName(string $subjectName): static
    {
        $this->subjectName = $subjectName;

        return $this;
    }

    public function getSubjectCode(): ?string
    {
        return $this->subjectCode;
    }

    public function setSubjectCode(string $subjectCode): static
    {
        $this->subjectCode = $subjectCode;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): static
    {
        $this->department = $department;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, ClassEntity>
     */
    public function getClassEntities(): Collection
    {
        return $this->classEntities;
    }

    public function addClassEntity(ClassEntity $classEntity): static
    {
        if (!$this->classEntities->contains($classEntity)) {
            $this->classEntities->add($classEntity);
            $classEntity->setSubject($this);
        }

        return $this;
    }

    public function removeClassEntity(ClassEntity $classEntity): static
    {
        if ($this->classEntities->removeElement($classEntity)) {
            // set the owning side to null (unless already changed)
            if ($classEntity->getSubject() === $this) {
                $classEntity->setSubject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Session>
     */
    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(Session $session): static
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setSubject($this);
        }

        return $this;
    }

    public function removeSession(Session $session): static
    {
        if ($this->sessions->removeElement($session)) {
            // set the owning side to null (unless already changed)
            if ($session->getSubject() === $this) {
                $session->setSubject(null);
            }
        }

        return $this;
    }
}
