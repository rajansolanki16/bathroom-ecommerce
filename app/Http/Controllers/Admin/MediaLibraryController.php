<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaLibraryController extends Controller

{

    public function picker()
    {
        $media = Media::where('model_type', 'App\\Models\\Setting')
            ->where('collection_name', 'uploads')
            ->latest()->paginate(30);
        return view('admin.media-library.picker', compact('media'));
    }
    public function index()
    {
        // Only show media attached to Setting model and 'uploads' collection
        $media = Media::where('model_type', 'App\\Models\\Setting')
            ->where('collection_name', 'uploads')
            ->latest()->paginate(30);
        return view('admin.media-library.index', compact('media'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);
        $model = \App\Models\Setting::first();
        $uploaded = [];
        $files = $request->file('file');
        if (!is_array($files)) {
            $files = [$files];
        }
        foreach ($files as $file) {
            $media = $model->addMedia($file)->toMediaCollection('uploads');
            $uploaded[] = $media;
        }
        return response()->json(['success' => true, 'media' => $uploaded]);
    }

    public function destroy(Media $media)
    {
        $media->delete();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Media $media)
    {
        $media->custom_properties = array_merge($media->custom_properties ?? [], [
            'title' => $request->input('title'),
            'alt' => $request->input('alt'),
            'description' => $request->input('description'),
        ]);
        $media->save();
        return response()->json(['success' => true, 'media' => $media]);
    }

    public function show(Media $media)
    {
        return response()->json([
            'id' => $media->id,
            'file_name' => $media->file_name,
            'size' => $media->size,
            'mime_type' => $media->mime_type,
            'created_at' => $media->created_at,
            'url' => $media->getUrl(),
            'alt' => $media->getCustomProperty('alt'),
            'title' => $media->getCustomProperty('title'),
            'description' => $media->getCustomProperty('description'),
        ]);
    }

    // Add image manipulation using conversions
    // In Setting model, add registerMediaConversions
}
