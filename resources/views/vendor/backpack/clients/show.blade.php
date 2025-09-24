@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>Megbízó: {{ $client->name }}</h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="clientTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Kapcsolattartók</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card p-3">
                        <p><strong>Név:</strong> {{ $client->name }}</p>
                        <p><strong>Cég:</strong> {{ $client->company ?: '-' }}</p>
                        <p><strong>Email:</strong> {{ $client->email ?: '-' }}</p>
                        <p><strong>Telefon:</strong> {{ $client->phone ?: '-' }}</p>
                        <p><strong>Mobil:</strong> {{ $client->mobile ?: '-' }}</p>
                        @if($client->user)
                            <p><strong>Referens:</strong> {{ $client->user->name }}</p>
                        @endif
                        <p><strong>Cím:</strong> {{ $client->address ?: '-' }}</p>
                        <p><strong>Város:</strong> {{ $client->city ?: '-' }}</p>
                        <p><strong>Irányítószám:</strong> {{ $client->zip_code ?: '-' }}</p>
                        <p><strong>Ország:</strong> {{ $client->country ?: '-' }}</p>
                        <p><strong>Státusz:</strong> {{ $client->status }}</p>
                        <p><strong>Megjegyzések:</strong> {{ $client->notes ?: '-' }}</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="contacts" role="tabpanel">
                    <div class="card p-3">
                        @if($client->contacts->isEmpty())
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->contacts as $contact)
                                            <tr>
                                                <td>{{ $contact->name }}</td>
                                                <td>{{ $contact->relationship ?: '-' }}</td>
                                                <td>{{ $contact->phone ?: '-' }}</td>
                                                <td>{{ $contact->email ?: '-' }}</td>
                                                <td>{{ $contact->notes ?: '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="tab-pane fade" id="documents" role="tabpanel">
                    <div class="card p-3">
                        @if($client->documents->isEmpty())
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($client->documents as $document)
                                            <tr>
                                                <td>{{ $document->name ?: 'Nincs név' }}</td>
                                                <td>{{ $document->category_name ?: '-' }}</td>
                                                <td><a href="{{ $document->file_url }}" target="_blank">{{ $document->original_name }}</a></td>
                                                <td>{{ $document->file_size_human }}</td>
                                                <td>{{ $document->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
