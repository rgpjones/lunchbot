<?php


namespace RgpJones\Rotaman\Storage;


interface Storage
{
    public function load(): array;

    public function save($data);
}