<?php

namespace RgpJones\Rotabot\Storage;

class FileStorage implements Storage
{
    const STORAGE_FILE_PATH = '../../../../var';

    private $channel;

    private $file;

    public function __construct($channel)
    {
        $file = $this->getStorageFilename($channel);

        if (!$this->fileIsWritable($file)) {
            throw new \InvalidArgumentException(
                sprintf('The storage file or containing directory for #%s cannot be written to.', $channel)
            );
        }

        $this->channel = $channel;
        $this->file = $file;
    }

    public function load(): array
    {
        return (file_exists($this->file))
            ? json_decode(file_get_contents($this->file), true)
            : [];
    }

    public function save($data)
    {
        file_put_contents($this->file, json_encode($data));
    }

    protected function getStorageFilename($channel): string
    {
        return sprintf('%s/%s/%s.json', __DIR__, self::STORAGE_FILE_PATH, $channel);
    }

    protected function fileIsWritable(string $file): bool
    {
        return (file_exists($file) && is_writable($file))
            || is_writable(dirname($file));
    }
}
