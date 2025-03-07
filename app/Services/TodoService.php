<?php

namespace App\Services;

use App\Models\Todo;

class TodoService
{
    public function createItem(array $data)
    {
        return Todo::create($data);
    }

}
