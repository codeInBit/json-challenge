<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Jobs\ProcessJsonFile;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileUploadRequest;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class FileUploadController extends Controller
{
     /**
     * Get the uploaded file and save it locally, then begin upload from the locally stored file.
     *
     * @param FileUploadRequest $request
     * @return JsonResponse
     */
    public function __invoke(FileUploadRequest $request): JsonResponse
    {
        $uploadedFile = $request['file'];

        $originalFileName = Str::slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME));

        $fileExtension = $uploadedFile->getClientOriginalExtension();

        $newFileName = $originalFileName .'-' . time() . '.' . $fileExtension;

        $savedFilePath = $uploadedFile->storeAs('files', $newFileName);
        
        if ($fileExtension == 'json') {
            ProcessJsonFile::dispatch($savedFilePath);
        } else {
            return $this->errorResponse(
                null,
                'Sorry, only JSON file are supported at the moment.',
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $this->successResponse(
            [],
            'The file has been uploaded and it is being processed',
            Response::HTTP_CREATED
        );
    }
}
