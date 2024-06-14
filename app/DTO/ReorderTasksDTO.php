<?php

declare(strict_types=1);

namespace App\DTO;

class ReorderTasksDTO
{
    /**
     * Associative array mapping task IDs to their new priorities.
     *
     * @var array
     */
    public array $order;

    public function __construct(array $order)
    {
        $this->order = $order;
    }
}
