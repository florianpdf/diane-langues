<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

// For translation
use Knp\DoctrineBehaviors\Contract\Entity\TranslatableInterface;
use Knp\DoctrineBehaviors\Model\Translatable\TranslatableTrait;
use ORMBehaviors\Translatable\Translatable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DomainRepository")
 * @Vich\Uploadable
 */
class Domain implements TranslatableInterface
{
    // For translation
    use TranslatableTrait;

    public function __call($method, $arguments)
    {
        $method = ('get' === substr($method, 0, 3) || 'set' === substr($method, 0, 3)) ? $method : 'get'. ucfirst($method);

        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    public function __get($name)
    {
        $method = 'get'. ucfirst($name);
        $arguments = [];
        return $this->proxyCurrentLocaleTranslation($method, $arguments);
    }

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="domain_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }
}
