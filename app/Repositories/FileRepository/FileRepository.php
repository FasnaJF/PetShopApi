<?php

namespace App\Repositories\FileRepository;

use App\Repositories\BaseRepository;
use App\Models\File;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    public function __construct(File $file)
    {
        $this->model = $file;
    }

}
