<?php

namespace RgpJones\Rotabot\Storage;

class NullStorageFactory implements StorageFactory
{
    public function getStorage(string $id): Storage
    {
        return new NullStorage();
    }
}
