<?php

namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class UserMediaController extends Controller
{
    public function upload(Request $request)
    {
        // Validation
        $request->validate([
            'file' => 'required|file|max:10240',
            'type' => 'required|in:image,video'
        ]);

        $file = $request->file('file');
        $type = $request->input('type');

        // Nom unique
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        // Stockage
        $path = $file->storeAs(
            "uploads/{$type}s",
            $filename,
            'public'
        );

        // URL publique
        $url = asset('storage/' . $path);

        // Retour simple
        return response()->json([
            'success' => true,
            'url' => $url,
            'filename' => $filename
        ]);
    }
}
