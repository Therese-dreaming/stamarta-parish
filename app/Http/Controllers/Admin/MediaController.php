<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with('uploader');

        // Filter by type
        if ($request->filled('type')) {
            if ($request->type === 'image') {
                $query->images();
            } elseif ($request->type === 'document') {
                $query->documents();
            }
        }

        // Filter by folder
        if ($request->filled('folder')) {
            $query->inFolder($request->folder);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('original_name', 'like', '%' . $request->search . '%')
                  ->orWhere('alt_text', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $media = $query->orderBy('created_at', 'desc')->paginate(24);

        // Get unique types and folders for filters
        $types = Media::selectRaw('CASE WHEN mime_type LIKE "image/%" THEN "image" ELSE "document" END as type')
            ->distinct()
            ->pluck('type');

        $folders = Media::distinct()->pluck('folder');

        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('cms.media.index', compact('media', 'types', 'folders', 'isStaff'));
    }

    public function create()
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('cms.media.create', compact('isStaff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'folder' => 'nullable|string|max:255',
        ]);

        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
            $folder = $request->input('folder', 'images');
            $filePath = $file->storeAs("media/{$folder}", $fileName, 'public');

            $media = Media::create([
                'original_name' => $file->getClientOriginalName(),
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'folder' => $folder,
                'uploaded_by' => Auth::id(),
            ]);

            $uploadedFiles[] = $media;
        }

        return redirect()->route('admin.cms.media.index')
            ->with('success', count($uploadedFiles) . ' file(s) uploaded successfully.');
    }

    public function edit(Media $media)
    {
        return response()->json($media);
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'folder' => 'nullable|string|max:255',
        ]);

        $media->update($validated);

        return response()->json(['success' => true]);
    }

    public function destroy(Media $media)
    {
        // Delete file from storage
        if (Storage::disk('public')->exists($media->file_path)) {
            Storage::disk('public')->delete($media->file_path);
        }

        $media->delete();

        return response()->json(['success' => true]);
    }
} 