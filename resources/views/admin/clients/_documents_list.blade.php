@if($documents->isEmpty())
    <div class="alert alert-info">Nincsenek feltöltött dokumentumok.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Kategória</th>
                    <th>Fájl</th>
                    <th>Méret</th>
                    <th>Feltöltve</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($documents as $document)
                <tr>
                    <td>{{ $document->name ?: 'Nincs név' }}</td>
                    <td>
                        <span class="badge bg-secondary">
                            {{ $document->category_name ?? 'Ismeretlen' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-decoration-none">
                            <i class="fas fa-file me-1"></i>{{ $document->original_name }}
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteDocument({{ $document->id }})">
                            <i class="fas fa-trash"></i> Törlés
                        </button>
                    </td>
                    <td>{{ $document->file_size_human ?? '0 B' }}</td>
                    <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

