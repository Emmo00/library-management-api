<?php

namespace App\Enums;

enum BookStatus
{
    const AVAILABLE = "AVAILABLE";
    const BORROWED = "BORROWED";

    static public function all()
    {
        return [
            static::AVAILABLE,
            static::BORROWED,
        ];
    }
}
