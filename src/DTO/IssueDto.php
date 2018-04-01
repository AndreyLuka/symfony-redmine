<?php

namespace App\DTO;

class IssueDto
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var int
     */
    private $doneRatio;

    /**
     * @var string
     */
    private $createdOn;

    /**
     * @var string
     */
    private $updatedOn;

    /**
     * @var ProjectDto
     */
    private $project;

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
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
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
     * @return string
     */
    public function getStartDate(): string
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate(string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return int
     */
    public function getDoneRatio(): int
    {
        return $this->doneRatio;
    }

    /**
     * @param int $doneRatio
     */
    public function setDoneRatio(int $doneRatio): void
    {
        $this->doneRatio = $doneRatio;
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

    /**
     * @return ProjectDto
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param ProjectDto $project
     */
    public function setProject(ProjectDto $project): void
    {
        $this->project = $project;
    }
}
