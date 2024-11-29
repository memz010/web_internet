<?php

namespace App\Services;

use App\Models\File;
use App\Models\User;

class FileService
{
    public function createFile($data)
    {
        return File::create($data);
    }

    public function getFilesForUser(User $user)
    {
        return $user->files()->with('group')->get();
    }

    public function checkOutFile($fileId, User $user)
    {
        $file = File::findOrFail($fileId);

        if ($file->status !== 'available') {
            return null; // File is already checked out
        }

        $file->status = 'checked_out';
        $file->user_id = $user->id;
        $file->save();

        return $file;
    }

    public function checkInFile($fileId, User $user)
    {
        $file = File::findOrFail($fileId);

        if ($file->user_id !== $user->id) {
            return null; // User does not own this file
        }

        $file->status = 'available';
        $file->user_id = null;
        $file->save();

        return $file;
    }
}
