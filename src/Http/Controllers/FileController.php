<?php

namespace NovaFileShelf\Http\Controllers;

use Illuminate\Support\Facades\File;
use NovaFileShelf\Shelf;

class FileController
{
    public function show($key)
    {
        $shelf = new Shelf($key);

        if ($shelf->isEmpty()) {
            return response('', 404);
        }

        return response()->download($shelf->path());
    }
}
