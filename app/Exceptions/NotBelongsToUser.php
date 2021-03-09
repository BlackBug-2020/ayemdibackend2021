<?php

namespace App\Exceptions;

use Exception;

class NotBelongsToUser extends Exception
{
    public function render()
    {
        return ['error' => 'Not belongs to user'];
    }
}
