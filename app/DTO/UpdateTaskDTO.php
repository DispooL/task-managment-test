<?php

declare(strict_types=1);

namespace App\DTO;

class UpdateTaskDTO
{
    public int $id;
    public string $name;
    public int $priority;

    public function __construct(int $id, string $name, int $priority)
    {
        $this->id = $id;
        $this->name = $name;
        $this->priority = $priority;
    }
}
