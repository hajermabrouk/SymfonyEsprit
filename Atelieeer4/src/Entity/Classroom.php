<?php

namespace App\Entity;

use App\Repository\ClassroomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassroomRepository::class)]
class Classroom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column] // #[ORM\Column(name:'ref')] tbdlo houni ki thebo ytbdl kn fl base de donnée
    private ?int $ref = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $createAT = null;

    #[ORM\OneToMany(mappedBy: 'classroom', targetEntity: Student::class)] //mappedBy dima onetomany 
    private Collection $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getRef(): ?int
    {
        return $this->ref;
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

    public function getCreateAT(): ?\DateTimeInterface
    {
        return $this->createAT;
    }

    public function setCreateAT(\DateTimeInterface $createAT): static
    {
        $this->createAT = $createAT;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setClassroom($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getClassroom() === $this) {
                $student->setClassroom(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->name;
    }
}