<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::latest()->get();

        return view('photos.index', compact('photos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption' => ['required', 'string', 'max:255'],
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:2048',
            ],
        ], [
            'caption.required' => 'Please enter a caption.',
            'image.required' => 'Please select an image.',
            'image.image' => 'The selected file must be a real image.',
            'image.mimes' => 'Only JPG, PNG, GIF, and WEBP images are allowed.',
            'image.max' => 'The image must not exceed 2 MB.',
        ]);

        $file = $request->file('image');

        $originalName = $file->getClientOriginalName();
        $size = $file->getSize();

        $extension = $file->extension();
        $filename = (string) Str::uuid() . '.' . $extension;

        $path = null;

        try {
            $path = $file->storeAs(
                'photos',
                $filename,
                'public'
            );

            if ($path === false) {
                throw new \RuntimeException(
                    'The image could not be stored.'
                );
            }

            Photo::create([
                'caption' => $validated['caption'],
                'path' => $path,
                'original_name' => $originalName,
                'size' => $size,
            ]);
        } catch (Throwable $e) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }

            report($e);

            return back()
                ->withInput($request->except('image'))
                ->withErrors([
                    'image' => 'Upload failed. Please try again.',
                ]);
        }

        return redirect()
            ->route('photos.index')
            ->with('success', 'Photo uploaded successfully!');
    }

    public function destroy(Photo $photo)
    {
        $disk = Storage::disk('public');

        if (!$disk->exists($photo->path)) {
            return back()->with(
                'error',
                'The photo file is missing. Nothing was deleted.'
            );
        }

        // Keep a backup while deleting the database record.
        $backup = $disk->get($photo->path);

        if ($backup === null) {
            return back()->with(
                'error',
                'The photo could not be read.'
            );
        }

        if (!$disk->delete($photo->path)) {
            return back()->with(
                'error',
                'The photo file could not be deleted.'
            );
        }

        try {
            $photo->delete();
        } catch (Throwable $e) {
            // Restore the file if database deletion fails.
            $disk->put($photo->path, $backup);

            report($e);

            return back()->with(
                'error',
                'Delete failed. Please check storage and database.'
            );
        }

        return redirect()
            ->route('photos.index')
            ->with('success', 'Photo deleted successfully!');
    }
}
