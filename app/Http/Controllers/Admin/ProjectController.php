<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Models\Partners;
use App\Models\Project;
use App\Models\ProjectDocument;
use App\Models\UniqueCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::with(['user', 'partner'])->latest()->paginate(20);
        
        return view('admin.projects.index', [
            'title' => 'Projektek',
            'pageTitle' => 'Projektek',
            'projects' => $projects,
            'indexRoute' => 'admin.projects.index',
            'createRoute' => 'admin.projects.create',
            'editRoute' => 'admin.projects.edit',
            'destroyRoute' => 'admin.projects.destroy',
            'showRoute' => 'admin.projects.show',
        ]);
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $partners = Partners::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $nextCode = UniqueCode::getNextCode();
        
        return view('admin.projects.create', [
            'title' => 'Új projekt',
            'pageTitle' => 'Új projekt',
            'users' => $users,
            'partners' => $partners,
            'nextCode' => $nextCode,
            'storeRoute' => 'admin.projects.store',
            'indexRoute' => 'admin.projects.index',
        ]);
    }

    public function store(ProjectRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            // Set user_id if not provided
            if (empty($validated['user_id'])) {
                $validated['user_id'] = auth()->id();
            }
            
            // Handle images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/temp', 'public');
                    $imagePaths[] = $path;
                }
                $validated['images'] = json_encode($imagePaths);
            } else {
                $validated['images'] = json_encode([]);
            }
            
            // Create project
            $project = Project::create($validated);
            
            // Move images from temp to project directory
            if ($project->images) {
                $images = json_decode($project->images, true) ?? [];
                $finalPaths = [];
                foreach ($images as $imgPath) {
                    if (Storage::disk('public')->exists($imgPath)) {
                        $newPath = 'uploads/' . $project->id . '/' . basename($imgPath);
                        Storage::disk('public')->makeDirectory('uploads/' . $project->id);
                        Storage::disk('public')->move($imgPath, $newPath);
                        $finalPaths[] = $newPath;
                    }
                }
                $project->images = json_encode($finalPaths);
                $project->save();
            }
            
            // Update unique code
            UniqueCode::updateCode((int) $validated['project_code']);
            
            return redirect()->route('admin.projects.show', $project->id)
                ->with('success', 'Projekt sikeresen létrehozva.');
        } catch (\Exception $e) {
            \Log::error('Project creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hiba történt a projekt létrehozása során: ' . $e->getMessage());
        }
    }

    public function show(Project $project): View
    {
        $project->load(['user', 'partner', 'properties']);
        $documents = ProjectDocument::where('project_id', $project->id)->get();
        
        return view('admin.projects.show', [
            'title' => 'Projekt részletei',
            'pageTitle' => 'Projekt: ' . $project->name,
            'project' => $project,
            'documents' => $documents,
            'indexRoute' => 'admin.projects.index',
            'editRoute' => 'admin.projects.edit',
        ]);
    }

    public function edit(Project $project): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $partners = Partners::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        // Decode images if JSON
        $images = [];
        if ($project->images) {
            $decoded = json_decode($project->images, true);
            $images = is_array($decoded) ? $decoded : [];
        }
        
        return view('admin.projects.edit', [
            'title' => 'Projekt szerkesztése',
            'pageTitle' => 'Projekt szerkesztése',
            'project' => $project,
            'users' => $users,
            'partners' => $partners,
            'images' => $images,
            'updateRoute' => 'admin.projects.update',
            'indexRoute' => 'admin.projects.index',
        ]);
    }

    public function update(ProjectRequest $request, Project $project): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            // Handle images
            $existingImages = json_decode($project->images, true) ?? [];
            
            // Handle existing images removal
            if ($request->has('existing_images')) {
                $keptImages = json_decode($request->input('existing_images'), true) ?? [];
                $existingImages = array_intersect($existingImages, $keptImages);
            }
            
            // Handle new images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/' . $project->id, 'public');
                    $existingImages[] = $path;
                }
            }
            
            $validated['images'] = json_encode($existingImages);
            
            // Update project
            $project->update($validated);
            
            // Update unique code
            UniqueCode::updateCode((int) $validated['project_code']);
            
            return redirect()->route('admin.projects.show', $project->id)
                ->with('success', 'Projekt sikeresen frissítve.');
        } catch (\Exception $e) {
            \Log::error('Project update error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hiba történt a projekt frissítése során: ' . $e->getMessage());
        }
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();
        
        return redirect()->route('admin.projects.index')
            ->with('success', 'Projekt sikeresen törölve.');
    }

    /**
     * Upload a document for a project
     */
    public function uploadDocument(\Illuminate\Http\Request $request, $projectId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:blueprint,contract,foundation,permit,invoice,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $project = Project::findOrFail($projectId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Store file in documents directory
            $path = $file->storeAs('documents/projects', $filename, 'public');

            // Create document record
            $document = ProjectDocument::create([
                'project_id' => $projectId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            $documents = ProjectDocument::where('project_id', $projectId)->get();
            $documentsHtml = view('admin.projects._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Project document upload error', [
                'project_id' => $projectId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a feltöltés során: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a project document
     */
    public function deleteDocument($documentId): JsonResponse
    {
        try {
            $document = ProjectDocument::findOrFail($documentId);
            $projectId = $document->project_id;

            // Delete the document (this will also delete the file via model event)
            $document->delete();

            $documents = ProjectDocument::where('project_id', $projectId)->get();
            $documentsHtml = view('admin.projects._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Project document delete error', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a törlés során: '.$e->getMessage(),
            ], 500);
        }
    }
}

