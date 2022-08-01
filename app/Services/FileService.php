<?php

namespace App\Services;

use App\Repositories\FileRepository\FileRepositoryInterface;

class FileService
{
    private FileRepositoryInterface $fileRepo;

    public function __construct(FileRepositoryInterface $fileRepo)
    {
        $this->fileRepo = $fileRepo;
    }

    public function getFileById($id)
    {
        return $this->fileRepo->getById($id);
    }

    public function createFile($data)
    {
        return $this->fileRepo->create($data);
    }

    public function deleteFile($id)
    {
        return $this->fileRepo->deleteById($id);
    }

    public function getFileByEmail($email)
    {
        return $this->fileRepo->getByEmail($email);
    }

    public function updateFile($id, $data)
    {
        return $this->fileRepo->updateById($id, $data);
    }
}
