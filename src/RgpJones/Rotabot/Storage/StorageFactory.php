<?php

namespace RgpJones\Rotabot\Storage;

interface StorageFactory
{
    public function getStorage(string $id): Storage;
}
