<?php

namespace App\Enums;

enum UserRole
{
    const ADMIN = "ADMIN";
    const LIBRARIAN = "LIBRARIAN";
    const MEMBER = "MEMBER";

    static public function all()
    {
        return [
            static::ADMIN,
            static::LIBRARIAN,
            static::MEMBER,
        ];
    }
}
