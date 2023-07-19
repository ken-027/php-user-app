<?php

namespace Lib;

class Upload
{
    private $fileName = '';
    public function __construct(private array &$image)
    {}

    public function store(int $id): bool
    {
        $image =  &$this->image;
        $pathExt = pathinfo($image['full_path'], PATHINFO_EXTENSION);
        $fileName = "user_avatar_{$id}.{$pathExt}";
        $image['name'] = $fileName;
        return move_uploaded_file($image['tmp_name'], STORAGE . "/{$fileName}");
    }

    public function fileName(): string
    {
        return $this->fileName;
    }
}
