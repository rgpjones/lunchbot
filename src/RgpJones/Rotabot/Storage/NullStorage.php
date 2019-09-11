<?php

namespace RgpJones\Rotabot\Storage;

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
