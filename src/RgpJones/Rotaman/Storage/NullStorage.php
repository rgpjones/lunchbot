<?php

namespace RgpJones\Rotaman\Storage;

class NullStorage implements Storage
{

    public function load(): array
    {
        return [];
    }

    public function save($data)
    {
    }
}