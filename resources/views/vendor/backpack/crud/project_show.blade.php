@extends(backpack_view('blank'))

@php
    $project = $entry;
@endphp

@section('content')
    <style>
        .project-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1);
        }

        .project-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .project-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .action-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            color: white;
        }

        .btn-edit {
            background: linear-gradient(45deg, #28a745, #20c997);
            color: white;
        }

        .btn-delete {
            background: linear-gradient(45deg, #dc3545, #e83e8c);
            color: white;
        }

        .info-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
        }

        .info-card h5 {
            color: #495057;
            font-weight: 600;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f8f9fa;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 150px;
        }

        .info-value {
            color: #495057;
            text-align: right;
            flex: 1;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .image-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .image-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .image-item:hover {
            transform: scale(1.05);
        }

        .image-item img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }



        .table-responsive {
            margin-top: 1rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    </style>

    <div class="container-fluid">
        <!-- Project Header -->
        <div class="project-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="project-title">{{ $project->title }}</h1>
                    <p class="project-subtitle">
                        <strong>{{ $project->project_code }}</strong> •
                        {{ $project->name }}
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="action-buttons">
                        <a href="{{ backpack_url('project/'.$project->id.'/edit') }}" class="action-btn btn-edit">
                            <i class="la la-edit"></i> Szerkesztés
                        </a>
                        <button class="action-btn btn-delete" onclick="deleteProject({{ $project->id }})">
                            <i class="la la-trash"></i> Törlés
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Basic Information -->
            <div class="col-md-6">
                <div class="info-card">
                    <h5><i class="la la-info-circle"></i> Alapadatok</h5>
                    <div class="info-row">
                        <span class="info-label">Projekt kód:</span>
                        <span class="info-value"><strong>{{ $project->project_code }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Név:</span>
                        <span class="info-value"><strong>{{ $project->name }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Összefoglaló:</span>
                        <span class="info-value">{{ $project->title }}</span>
                    </div>
                    @if($project->partner)
                    <div class="info-row">
                        <span class="info-label">Partner:</span>
                        <span class="info-value">{{ $project->partner->name }}</span>
                    </div>
                    @endif
                    @if($project->user)
                    <div class="info-row">
                        <span class="info-label">Referens:</span>
                        <span class="info-value">{{ $project->user->name }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="info-label">Létrehozva:</span>
                        <span class="info-value">{{ $project->created_at->format('Y-m-d H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Módosítva:</span>
                        <span class="info-value">{{ $project->updated_at->format('Y-m-d H:i') }}</span>
                    </div>
                </div>

                <!-- Storage Information -->
                <div class="info-card">
                    <h5><i class="la la-boxes"></i> Tároló információk</h5>
                    <div class="info-row">
                        <span class="info-label">Tárolók száma:</span>
                        <span class="info-value"><strong>{{ $project->storage_count ?? 0 }}</strong></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tároló típusa:</span>
                        <span class="info-value">
                            @if($project->storage_type === 'fixed')
                                <span class="badge badge-primary">Fixen hozzárendelt</span>
                            @else
                                <span class="badge badge-secondary">Bármelyik választható</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Kötelező megvásárolni:</span>
                        <span class="info-value">
                            @if($project->is_required_storage)
                                <span class="badge badge-danger">Igen</span>
                            @else
                                <span class="badge badge-success">Nem</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description and Images -->
            <div class="col-md-6">
                <!-- Description -->
                @if($project->description)
                <div class="info-card">
                    <h5><i class="la la-file-text"></i> Leírás</h5>
                    <div class="mt-3">
                        {!! $project->description !!}
                    </div>
                </div>
                @endif

                <!-- Images -->
                @if($project->images)
                <div class="info-card">
                    <h5><i class="la la-images"></i> Képek</h5>
                    <div class="image-gallery">
                        @foreach(json_decode($project->images) as $image)
                        <div class="image-item">
                            <img src="{{ Storage::url($image) }}" alt="{{ $project->title }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>


    <!-- JavaScript for actions -->
    <script>
        function deleteProject(projectId) {
            if (confirm('Biztosan törli ezt a projektet? Ez a művelet nem vonható vissza!')) {
                fetch(`/admin/project/${projectId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        window.location.href = '/admin/project';
                    } else {
                        alert('Hiba történt a törlés során');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hiba történt a törlés során');
                });
            }
        }

        function deleteDocument(documentId) {
            if (!confirm("Biztosan törölni szeretné ezt a dokumentumot?")) {
                return;
            }

            fetch("/admin/project/document/" + documentId + "/delete", {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    "Content-Type": "application/json",
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Dokumentum sikeresen törölve!");
                    location.reload();
                } else {
                    alert("Hiba a törlés során: " + (data.message || "Ismeretlen hiba"));
                }
            })
            .catch(error => {
                console.error("Delete error:", error);
                alert("Hiba a törlés során: " + error.message);
            });
        }

    </script>
@endsection
