<?php

namespace NovaFileShelf;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class Shelf
{
    public string $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function get(): ?string
    {
        if ($this->isEmpty()) {
            return null;
        }

        return File::get($this->path());
    }

    /**
     * @todo store the file in a directory so you are able to store and display the file's name
     *       so it ends up like: storage/app/nova-file-shelf/key-name/File Name.xlsx
     *
     * @todo store using $file->storeAs($this->directory(), $this->key, ['disk' => config('nova-file-shelf.disk')])
     */
    public function put(UploadedFile $file): void
    {
        File::ensureDirectoryExists($this->directory());

        File::put($this->path(), $file->get());
    }

    public function isEmpty(): bool
    {
        return ! File::exists($this->path());
    }

    public function directory(): string
    {
        return storage_path('app/nova-file-shelf');
    }

    public function path(): string
    {
        return $this->directory() . '/' . $this->key;
    }

    public function metadata(): array
    {
        $path = $this->path();

        return [
            'name' => File::name($path),
            'mimeType' => File::mimeType($path),
            'timestamp' => File::lastModified($path),
            'downloadUrl' => "/nova-vendor/nova-file-shelf/files/{$this->key}",
        ];
    }
}
