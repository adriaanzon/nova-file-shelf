<?php

namespace NovaFileShelf\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use NovaFileShelf\Shelf;

class ShelfController
{
    public function show(string $key)
    {
        $shelf = new Shelf($key);

        if ($shelf->isEmpty()) {
            return response()->json()->setData(null);
        }

        return $shelf->metadata();
    }

    public function update(Request $request, string $key)
    {
        $request->validate(['file' => 'required|file']);

        $shelf = new Shelf($key);
        $shelf->put($request->file('file'));

        return $shelf->metadata();
    }
}
