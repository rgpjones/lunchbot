<?php


namespace RgpJones\Rotabot\Storage;

interface Storage
{
    public function load(): array;

    public function save($data);
}
