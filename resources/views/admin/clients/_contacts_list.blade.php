@if($contacts->isEmpty())
    <div class="alert alert-info">Nincsenek hozzáadott kapcsolattartók.</div>
@else
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Név</th>
                    <th>Kapcsolat</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Megjegyzések</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->relationship ?: '-' }}</td>
                    <td>{{ $contact->phone ?: '-' }}</td>
                    <td>{{ $contact->email ?: '-' }}</td>
                    <td>{{ \Str::limit($contact->notes ?? '-', 50) }}</td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteContact({{ $contact->id }})">
                            <i class="fas fa-trash"></i> Törlés
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

