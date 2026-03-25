@if($documents->isEmpty())
    <div class="text-center p-4">
        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
        <p class="text-muted mb-0">Még nincsenek feltöltött dokumentumok.</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th><i class="fas fa-file me-1"></i>Név</th>
                    <th><i class="fas fa-tag me-1"></i>Kategória</th>
                    <th><i class="fas fa-paperclip me-1"></i>Fájl</th>
                    <th><i class="fas fa-weight me-1"></i>Méret</th>
                    <th><i class="fas fa-calendar me-1"></i>Feltöltve</th>
                    <th><i class="fas fa-cogs me-1"></i>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-{{ $document->file_type === 'application/pdf' ? 'pdf text-danger' : 'image text-primary' }} me-2"></i>
                            <span class="fw-semibold">{{ $document->name }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge bg-secondary rounded-pill px-3 py-2">{{ $document->category_name }}</span>
                    </td>
                    <td>
                        <a href="{{ $document->file_url }}" target="_blank" class="text-decoration-none fw-medium">
                            <i class="fas fa-external-link-alt me-1"></i>{{ $document->original_name }}
                        </a>
                    </td>
                    <td>
                        <small class="text-muted">{{ $document->file_size_human }}</small>
                    </td>
                    <td>
                        <small class="text-muted">{{ $document->created_at->format('Y-m-d H:i') }}</small>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteDocument({{ $document->id }})">
                            <i class="fas fa-trash me-1"></i>Törlés
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

