@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>Megbízó: {{ $partner->name }}</h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="partnerTabs" role="tablist">
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
                        <p><strong>Név:</strong> {{ $partner->name }}</p>
                        <p><strong>Cég:</strong> {{ $partner->company ?: '-' }}</p>
                        <p><strong>Email:</strong> {{ $partner->email ?: '-' }}</p>
                        <p><strong>Telefon:</strong> {{ $partner->phone ?: '-' }}</p>
                        <p><strong>Mobil:</strong> {{ $partner->mobile ?: '-' }}</p>
                        @if($partner->user)
                            <p><strong>Referens:</strong> {{ $partner->user->name }}</p>
                        @endif
                        <p><strong>Cím:</strong> {{ $partner->address ?: '-' }}</p>
                        <p><strong>Város:</strong> {{ $partner->city ?: '-' }}</p>
                        <p><strong>Irányítószám:</strong> {{ $partner->zip_code ?: '-' }}</p>
                        <p><strong>Ország:</strong> {{ $partner->country ?: '-' }}</p>
                        <p><strong>Státusz:</strong> {{ $partner->status }}</p>
                        <p><strong>Megjegyzések:</strong> {{ $partner->notes ?: '-' }}</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="contacts" role="tabpanel">
                    <div class="card p-3">
                        @if($partner->contacts->isEmpty())
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
                                        @foreach($partner->contacts as $contact)
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
                        @if($partner->documents->isEmpty())
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
                                        @foreach($partner->documents as $document)
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
