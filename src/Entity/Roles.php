<?php
namespace App\Entity;
use JsonSerializable;

enum Roles implements JsonSerializable
{
    case ROLE_USER;
    case ROLE_ADMIN;
    case ROLE_SUPER_ADMIN;

    public function jsonSerialize() : string
    {
        return $this->name; // Assuming you want to serialize the enum name
    }
}