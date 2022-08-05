<?php

namespace App\Repositories\FileRepository;

use App\Models\File;
use App\Repositories\BaseRepository;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    public function __construct(File $file)
    {
        $this->model = $file;
    }

}
