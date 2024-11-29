<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileRequest;
use App\Services\FileService;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function store(FileRequest $request)
    {
        $file = $this->fileService->createFile($request->validated() + ['user_id' => auth()->id()]);
        return response()->json($file, 201);
    }

    public function index()
    {
        $files = $this->fileService->getFilesForUser(auth()->user());
        return response()->json($files);
    }

    public function checkOut($id)
    {
        $file = $this->fileService->checkOutFile($id, auth()->user());
        if (!$file) {
            return response()->json(['message' => 'File is not available'], 400);
        }
        return response()->json($file);
    }

    public function checkIn($id)
    {
        $file = $this->fileService->checkInFile($id, auth()->user());
        if (!$file) {
            return response()->json(['message' => 'You do not own this file'], 403);
        }
        return response()->json($file);
    }
}
