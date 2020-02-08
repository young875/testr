<?php

namespace App\Entity;

use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarquesRepository")
 * @Vich\Uploadable()
 * @ORM\HasLifecycleCallbacks()
 */
class Marques
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @var File|null
     * @Vich\UploadableField(mapping="marque_image", fileNameProperty="filename")
     */
    private $imageFile;

    /**
     * @var string|null
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $marque;


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeSlug(){
        if(empty($this->slug)){
            $slugify = new Slugify();
            $this->slug = $slugify->slugify($this->marque);
        }
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     * @return Marques
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Cars", mappedBy="marques", orphanRemoval=true)
     */
    private $Cars;

    public function __construct()
    {
        $this->Cars = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Marques
     * @throws \Exception
     */
    public function setImageFile(?File $imageFile): Marques
    {
        $this->imageFile = $imageFile;
        if ($this->imageFile instanceof  UploadedFile) {
            $this->updated_at = new \DateTime('now');
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFilename(): ?string
    {
        return $this->filename;
    }

    /**
     * @param string|null $filename
     * @return Marques
     */
    public function setFilename(?string $filename): Marques
    {
        $this->filename = $filename;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection|Cars[]
     */
    public function getCars(): Collection
    {
        return $this->Cars;
    }

    public function addCar(Cars $car): self
    {
        if (!$this->Cars->contains($car)) {
            $this->Cars[] = $car;
            $car->setMarques($this);
        }

        return $this;
    }

    public function removeCar(Cars $car): self
    {
        if ($this->Cars->contains($car)) {
            $this->Cars->removeElement($car);
            // set the owning side to null (unless already changed)
            if ($car->getMarques() === $this) {
                $car->setMarques(null);
            }
        }

        return $this;
    }


}
