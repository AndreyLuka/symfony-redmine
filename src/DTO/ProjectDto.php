<?php

namespace App\DTO;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ProjectDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $identifier;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $status;

    /**
     * @var CustomFieldDto[]|ArrayCollection
     */
    private $customFields;

    /**
     * @var string
     */
    private $createdOn;

    /**
     * @var string
     */
    private $updatedOn;

    public function __construct()
    {
        $this->customFields = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    /**
     * @return null|string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param null|string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return Collection
     */
    public function getCustomFields(): Collection
    {
        return $this->customFields;
    }

    /**
     * @param array $customFields
     */
    public function setCustomFields(array $customFields): void
    {
        $this->customFields->clear();

        foreach ($customFields as $customField) {
            $this->addCustomFields($customField);
        }
    }

    /**
     * @param CustomFieldDto $customField
     */
    public function addCustomFields(CustomFieldDto $customField): void
    {
        $this->customFields->add($customField);
    }

    /**
     * @param CustomFieldDto $customField
     */
    public function removeCustomFields(CustomFieldDto $customField): void
    {
        $this->customFields->removeElement($customField);
    }

    /**
     * @return string
     */
    public function getCreatedOn(): string
    {
        return $this->createdOn;
    }

    /**
     * @param string $createdOn
     */
    public function setCreatedOn(string $createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return string
     */
    public function getUpdatedOn(): string
    {
        return $this->updatedOn;
    }

    /**
     * @param string $updatedOn
     */
    public function setUpdatedOn(string $updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }
}
