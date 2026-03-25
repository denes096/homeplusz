@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4>{{ $pageTitle }}</h4>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="customerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Kapcsolattartók</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="search-tab" data-bs-toggle="tab" data-bs-target="#search" type="button" role="tab">Keresési paraméterek</button>
                </li>
            </ul>

            <form action="{{ route($updateRoute, $customer->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Státusz <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Aktív" {{ old('status', $customer->status) == 'Aktív' ? 'selected' : '' }}>Aktív</option>
                                    <option value="Felfüggesztve" {{ old('status', $customer->status) == 'Felfüggesztve' ? 'selected' : '' }}>Felfüggesztve</option>
                                    <option value="Archív" {{ old('status', $customer->status) == 'Archív' ? 'selected' : '' }}>Archív</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="refId" class="form-label">Referens</label>
                                <select class="form-select @error('refId') is-invalid @enderror" id="refId" name="refId">
                                    <option value="">Válassz...</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('refId', $customer->refId) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('refId')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="kategoria" class="form-label">Kategória <span class="text-danger">*</span></label>
                                <select class="form-select @error('kategoria') is-invalid @enderror" id="kategoria" name="kategoria" required>
                                    <option value="maganszemely" {{ old('kategoria', $customer->kategoria) == 'maganszemely' ? 'selected' : '' }}>Magánszemély</option>
                                    <option value="beruhazo" {{ old('kategoria', $customer->kategoria) == 'beruhazo' ? 'selected' : '' }}>Beruházó</option>
                                </select>
                                @error('kategoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="name_0" class="form-label">Név <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name_0') is-invalid @enderror" id="name_0" name="name_0" value="{{ old('name_0', $customer->name_0) }}" required>
                                @error('name_0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_0" class="form-label">Telefonszám</label>
                                <input type="text" class="form-control @error('phone_0') is-invalid @enderror" id="phone_0" name="phone_0" value="{{ old('phone_0', $customer->phone_0) }}">
                                @error('phone_0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="azonosito1_0" class="form-label">Azonosító</label>
                                <input type="text" class="form-control @error('azonosito1_0') is-invalid @enderror" id="azonosito1_0" name="azonosito1_0" value="{{ old('azonosito1_0', $customer->azonosito1_0) }}">
                                @error('azonosito1_0')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email cím</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $customer->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="address" class="form-label">Cím</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $customer->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="note" class="form-label">Megjegyzés</label>
                                <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note', $customer->note) }}</textarea>
                                @error('note')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contacts" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Kapcsolattartók kezelése</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="contact-name" class="form-label">Név <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="contact-name" placeholder="Kapcsolattartó neve">
                                        </div>
                                        <div class="col-sm-6 col-md-2 mb-2">
                                            <label for="contact-relationship" class="form-label">Kapcsolat</label>
                                            <input type="text" class="form-control" id="contact-relationship" placeholder="pl. feleség, testvér">
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="contact-phone" class="form-label">Telefonszám</label>
                                            <input type="text" class="form-control" id="contact-phone" placeholder="Telefonszám">
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="contact-email" class="form-label">Email cím</label>
                                            <input type="email" class="form-control" id="contact-email" placeholder="email@cim.com">
                                        </div>
                                        <div class="col-sm-6 col-md-1 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" id="add-contact-btn" class="btn btn-primary d-block w-100">Hozzáadás</button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="contact-notes" class="form-label">Megjegyzések</label>
                                            <textarea class="form-control" id="contact-notes" rows="2" placeholder="Egyéb megjegyzések"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="contacts-list">
                                    @include('admin.customers._contacts_list', ['contacts' => $customer->contacts])
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="documents" role="tabpanel">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Dokumentumok kezelése</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="document-name" class="form-label">Dokumentum neve</label>
                                            <input type="text" class="form-control" id="document-name" placeholder="Ha üres, a fájl neve lesz használva">
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-2">
                                            <label for="document-category" class="form-label">Kategória</label>
                                            <select class="form-select" id="document-category">
                                                <option value="">Válassz kategóriát</option>
                                                <option value="contract">Szerződés</option>
                                                <option value="order">Megrendelő</option>
                                                <option value="purchase">Vételi</option>
                                                <option value="inspection">Szemle</option>
                                                <option value="other">Egyéb</option>
                                            </select>
                                        </div>
                                        <div class="col-sm-6 col-md-4 mb-2">
                                            <label for="document-file" class="form-label">Fájl <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control" id="document-file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                        </div>
                                        <div class="col-sm-6 col-md-2 mb-2">
                                            <label class="form-label">&nbsp;</label>
                                            <button type="button" id="upload-document-btn" class="btn btn-primary d-block w-100">Feltöltés</button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label for="document-description" class="form-label">Leírás</label>
                                            <textarea class="form-control" id="document-description" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div id="documents-list">
                                    @include('admin.customers._documents_list', ['documents' => $customer->documents])
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="search" role="tabpanel">
                        <div class="row">
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_price_min" class="form-label">Min ár</label>
                                <input type="number" class="form-control" id="p_price_min" name="p[price_min]" value="{{ old('p.price_min', $searchParams['price_min'] ?? '') }}">
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_price_max" class="form-label">Max ár</label>
                                <input type="number" class="form-control" id="p_price_max" name="p[price_max]" value="{{ old('p.price_max', $searchParams['price_max'] ?? '') }}">
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_ad_type" class="form-label">Típus</label>
                                <select class="form-select" id="p_ad_type" name="p[ad_type]">
                                    <option value="">Válassz...</option>
                                    <option value="sell" {{ old('p.ad_type', $searchParams['ad_type'] ?? '') == 'sell' ? 'selected' : '' }}>Eladó</option>
                                    <option value="rent" {{ old('p.ad_type', $searchParams['ad_type'] ?? '') == 'rent' ? 'selected' : '' }}>Kiadó</option>
                                    <option value="all" {{ old('p.ad_type', $searchParams['ad_type'] ?? '') == 'all' ? 'selected' : '' }}>Minden</option>
                                </select>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_property_types" class="form-label">Ingatlantípus</label>
                                <select class="form-select" id="p_property_types" name="p[property_types][]" multiple size="5">
                                    @foreach($propertyTypes as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('p.property_types', $searchParams['property_types'] ?? [])) ?: []) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Többszörös választás: Ctrl/Cmd + kattintás</small>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_property_subtypes" class="form-label">Ingatlan altípus</label>
                                <select class="form-select" id="p_property_subtypes" name="p[property_subtypes][]" multiple size="5">
                                    @foreach($propertySubtypes as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('p.property_subtypes', $searchParams['property_subtypes'] ?? [])) ?: []) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Többszörös választás: Ctrl/Cmd + kattintás</small>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_settlements" class="form-label">Település</label>
                                <select class="form-select" id="p_settlements" name="p[settlements][]" multiple size="5">
                                    @foreach($settlements as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('p.settlements', $searchParams['settlements'] ?? [])) ?: []) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Többszörös választás: Ctrl/Cmd + kattintás</small>
                            </div>
                            <div class="col-sm-6 col-md-3 mb-3">
                                <label for="p_settlement_parts" class="form-label">Település rész</label>
                                <select class="form-select" id="p_settlement_parts" name="p[settlement_parts][]" multiple size="5">
                                    @foreach($settlementParts as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('p.settlement_parts', $searchParams['settlement_parts'] ?? [])) ?: []) ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Többszörös választás: Ctrl/Cmd + kattintás</small>
                            </div>
                        </div>

                        @foreach($propAttrsCats as $propAttrCat)
                            @foreach($propAttrCat->propertyAttributes as $propAttr)
                                @if($propAttr->type == 'number')
                                    <div class="row">
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label for="p_{{ $propAttr->name }}_min" class="form-label">{{ $propAttr->label }} min</label>
                                            <input type="number" class="form-control" id="p_{{ $propAttr->name }}_min" name="p[{{ $propAttr->name }}_min]" value="{{ old('p.'.$propAttr->name.'_min', $searchParams[$propAttr->name.'_min'] ?? '') }}">
                                        </div>
                                        <div class="col-sm-6 col-md-3 mb-3">
                                            <label for="p_{{ $propAttr->name }}_max" class="form-label">{{ $propAttr->label }} max</label>
                                            <input type="number" class="form-control" id="p_{{ $propAttr->name }}_max" name="p[{{ $propAttr->name }}_max]" value="{{ old('p.'.$propAttr->name.'_max', $searchParams[$propAttr->name.'_max'] ?? '') }}">
                                        </div>
                                    </div>
                                @elseif($propAttr->type == 'select')
                                    @php
                                        $options = json_decode($propAttr->values, true) ?: [];
                                    @endphp
                                    <div class="col-sm-6 col-md-3 mb-3">
                                        <label for="p_{{ $propAttr->name }}" class="form-label">{{ $propAttr->label }}</label>
                                        <select class="form-select" id="p_{{ $propAttr->name }}" name="p[{{ $propAttr->name }}]">
                                            <option value="">Válassz...</option>
                                            @foreach($options as $key => $value)
                                            <option value="{{ $key }}" {{ old('p.'.$propAttr->name, $searchParams[$propAttr->name] ?? '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($propAttr->type == 'select_multiple')
                                    @php
                                        $options = json_decode($propAttr->values, true) ?: [];
                                    @endphp
                                    <div class="col-sm-6 col-md-3 mb-3">
                                        <label for="p_{{ $propAttr->name }}" class="form-label">{{ $propAttr->label }}</label>
                                        <select class="form-select" id="p_{{ $propAttr->name }}" name="p[{{ $propAttr->name }}][]" multiple size="5">
                                            @foreach($options as $key => $value)
                                            <option value="{{ $key }}" {{ in_array($key, old('p.'.$propAttr->name, $searchParams[$propAttr->name] ?? [])) ?: []) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Többszörös választás: Ctrl/Cmd + kattintás</small>
                                    </div>
                                @elseif($propAttr->type == 'checkbox')
                                    <div class="col-sm-6 col-md-3 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="p_{{ $propAttr->name }}" name="p[{{ $propAttr->name }}]" value="1" {{ old('p.'.$propAttr->name, $searchParams[$propAttr->name] ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="p_{{ $propAttr->name }}">
                                                {{ $propAttr->label }}
                                            </label>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <a href="{{ route($indexRoute) }}" class="btn btn-secondary">Mégse</a>
                    <button type="submit" class="btn btn-primary">Mentés</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const customerId = {{ $customer->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Add contact functionality
    const addContactBtn = document.getElementById('add-contact-btn');
    if (addContactBtn) {
        addContactBtn.addEventListener('click', function() {
            const name = document.getElementById('contact-name').value;
            const relationship = document.getElementById('contact-relationship').value;
            const phone = document.getElementById('contact-phone').value;
            const email = document.getElementById('contact-email').value;
            const notes = document.getElementById('contact-notes').value;

            if (!name.trim()) {
                alert('A kapcsolattartó neve kötelező!');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('relationship', relationship);
            formData.append('phone', phone);
            formData.append('email', email);
            formData.append('notes', notes);
            formData.append('_token', csrfToken);

            fetch(`/admin/customers/${customerId}/add-contact`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('contacts-list').innerHTML = data.contacts_html;
                    document.getElementById('contact-name').value = '';
                    document.getElementById('contact-relationship').value = '';
                    document.getElementById('contact-phone').value = '';
                    document.getElementById('contact-email').value = '';
                    document.getElementById('contact-notes').value = '';
                } else {
                    alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hiba történt a hozzáadás során');
            });
        });
    }

    // Delete contact functionality
    window.deleteContact = function(contactId) {
        if (!confirm('Biztosan törölni szeretné ezt a kapcsolattartót?')) {
            return;
        }

        fetch(`/admin/customers/contact/${contactId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('contacts-list').innerHTML = data.contacts_html;
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            alert('Hiba történt a törlés során');
        });
    };

    // Upload document functionality
    const uploadDocumentBtn = document.getElementById('upload-document-btn');
    if (uploadDocumentBtn) {
        uploadDocumentBtn.addEventListener('click', function() {
            const file = document.getElementById('document-file').files[0];
            if (!file) {
                alert('Kérjük, válasszon ki egy fájlt a feltöltéshez!');
                return;
            }

            const formData = new FormData();
            formData.append('name', document.getElementById('document-name').value || file.name);
            formData.append('category', document.getElementById('document-category').value || 'other');
            formData.append('file', file);
            formData.append('description', document.getElementById('document-description').value);
            formData.append('_token', csrfToken);

            uploadDocumentBtn.disabled = true;
            uploadDocumentBtn.textContent = 'Feltöltés...';

            fetch(`/admin/customers/${customerId}/upload-document`, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('documents-list').innerHTML = data.documents_html;
                    document.getElementById('document-name').value = '';
                    document.getElementById('document-category').value = '';
                    document.getElementById('document-file').value = '';
                    document.getElementById('document-description').value = '';
                } else {
                    alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hiba történt a feltöltés során');
            })
            .finally(() => {
                uploadDocumentBtn.disabled = false;
                uploadDocumentBtn.textContent = 'Feltöltés';
            });
        });
    }

    // Delete document functionality
    window.deleteDocument = function(documentId) {
        if (!confirm('Biztosan törölni szeretné ezt a dokumentumot?')) {
            return;
        }

        fetch(`/admin/customers/document/${documentId}/delete`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('documents-list').innerHTML = data.documents_html;
            } else {
                alert('Hiba: ' + (data.message || 'Ismeretlen hiba'));
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            alert('Hiba történt a törlés során');
        });
    };
});
</script>
@endpush
@endsection

