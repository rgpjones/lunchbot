<?php

namespace RgpJones\Rotabot\Storage;

class FileStorageFactory implements StorageFactory
{
    public function getStorage(string $id): Storage
    {
        return new FileStorage($id);
    }
}
