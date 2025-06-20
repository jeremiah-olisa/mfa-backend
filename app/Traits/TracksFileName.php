<?php

namespace App\Traits;

trait TracksFileName
{
    protected ?string $fileName = null;

    /**
     * Set the filename for this import
     */
    public function setFileName(string $name): void
    {
        $this->fileName = $name;
    }

    /**
     * Get the filename associated with this import
     */
    public function getFileName(): ?string
    {
        return $this->fileName;
    }
}