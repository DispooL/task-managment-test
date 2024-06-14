<?php

declare(strict_types=1);

namespace App\DTO;

class CreateTaskDTO
{
    public string $name;
    public int $priority;

    public function __construct(string $name, int $priority)
    {
        $this->name = $name;
        $this->priority = $priority;
    }
}
