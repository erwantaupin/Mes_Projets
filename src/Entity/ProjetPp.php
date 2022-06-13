<?php

namespace App\Entity;

use App\Repository\ProjetPpRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjetPpRepository::class)
 */
class ProjetPp
{
    const PROJET_ADDED_SUCCESSFULLY = 'PROJET_ADDED_SUCCESSFULLY';
    const PROJET_INVALID_FORM = 'PROJET_INVALID_FORM';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien_projet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $main_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lien_github;

    /**
     * @ORM\ManyToOne(targetEntity=UserPp::class, inversedBy="relation")
     * @ORM\JoinColumn(nullable=false)
     */
    private $relation;

    public function __construct()
    {
        $this->created_at = new \DateTime("now");
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function isArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getLienProjet(): ?string
    {
        return $this->lien_projet;
    }

    public function setLienProjet(string $lien_projet): self
    {
        $this->lien_projet = $lien_projet;

        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->main_image;
    }

    public function setMainImage(string $main_image): self
    {
        $this->main_image = $main_image;

        return $this;
    }

    public function getLienGithub(): ?string
    {
        return $this->lien_github;
    }

    public function setLienGithub(string $lien_github): self
    {
        $this->lien_github = $lien_github;

        return $this;
    }

    public function getRelation(): ?UserPp
    {
        return $this->relation;
    }

    public function setRelation(?UserPp $relation): self
    {
        $this->relation = $relation;

        return $this;
    }
}
