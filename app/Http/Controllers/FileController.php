<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFileRequest;
use App\Http\Resources\FileResource;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileController extends Controller
{
    private FileService $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Upload a file
     * @OA\Post (
     *     path="/api/v1/file/upload",
     *     operationId="file-upload",
     *     tags={"File"},
     *     security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required = true,
     *          @OA\MediaType(
     *              mediaType = "multipart/form-data",
     *              @OA\Schema(
     *                  type = "object",
     *                  required={
     *                            "file"
     *                  },
     *                  @OA\Property(
     *                      property = "file",
     *                      type = "string",
     *                      description = "file to upload",
     *                      format = "binary"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     * )
     */
    public function upload(CreateFileRequest $request)
    {
        $file = $request->file;
        $fileDetails['uuid'] = Str::uuid();
        $fileDetails['name'] = $file->getFilename();
        $fileDetails['path'] = 'public/pet-shop/' . $file->hashName();
        $fileDetails['size'] = $file->getSize();
        $fileDetails['type'] = $file->getMimeType();
        $file->move('pet-shop/', $file->hashName());

        $fileCreated = $this->fileService->createFile($fileDetails);
        return $this->returnResource(new FileResource($fileCreated));
    }

    /**
     * Read a file
     * @OA\Get (
     *     path="/api/v1/file/{uuid}",
     *     operationId="file-read",
     *     tags={"File"},
     *     @OA\Parameter(
     *         in="path",
     *         name="uuid",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *      @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found",
     *     ),
     *      @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *     ),
     *
     * )
     */
    public function show(Request $request)
    {
        $file = $this->fileService->getFileByUUID($request->uuid);
        if (!$file) {
            return $this->resourceNotFound("File not found");
        }
        $headers = [
            'Content-Type: image/jpeg',
            'content-length :' . $file->size,
            'Content-disposition:' . 'attachment; filename=' . pathinfo($file->path)['basename']
        ];
        return response()->download(
            'pet-shop/' . pathinfo($file->path)['basename'],
            pathinfo($file->path)['basename'],
            $headers
        );
    }
}
