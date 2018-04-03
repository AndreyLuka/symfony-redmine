<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class TimeEntryDto
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type("int")
     *
     * @var int
     */
    private $time;

    /**
     * @return int|null
     */
    public function getTime(): ?int
    {
        return $this->time;
    }

    /**
     * @param int $time
     */
    public function setTime(int $time): void
    {
        $this->time = $time;
    }
}
