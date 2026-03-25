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
            <ul class="nav nav-tabs mb-3" id="partnerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="address-tab" data-bs-toggle="tab" data-bs-target="#address" type="button" role="tab">Címadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab">Kapcsolattartó</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contacts-tab" data-bs-toggle="tab" data-bs-target="#contacts" type="button" role="tab">Kapcsolattartók</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="documents-tab" data-bs-toggle="tab" data-bs-target="#documents" type="button" role="tab">Dokumentumok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="other-tab" data-bs-toggle="tab" data-bs-target="#other" type="button" role="tab">Egyéb</button>
                </li>
            </ul>

            <form action="{{ route($updateRoute, $partner->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="basic" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Partner neve <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $partner->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Cég neve</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror" id="company" name="company" value="{{ old('company', $partner->company) }}">
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email cím</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $partner->email) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Telefonszám</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $partner->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="mobile" class="form-label">Mobil szám</label>
                                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ old('mobile', $partner->mobile) }}">
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="user_id" class="form-label">Referens</label>
                                <select class="form-select @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                                    <option value="">Válassz...</option>
                                    @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ old('user_id', $partner->user_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Státusz <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="Aktív" {{ old('status', $partner->status) == 'Aktív' ? 'selected' : '' }}>Aktív</option>
                                    <option value="Felfüggesztve" {{ old('status', $partner->status) == 'Felfüggesztve' ? 'selected' : '' }}>Felfüggesztve</option>
                                    <option value="Archív" {{ old('status', $partner->status) == 'Archív' ? 'selected' : '' }}>Archív</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="address" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="address" class="form-label">Cím</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $partner->address) }}">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="city" class="form-label">Város</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $partner->city) }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="zip_code" class="form-label">Irányítószám</label>
                                <input type="text" class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" value="{{ old('zip_code', $partner->zip_code) }}">
                                @error('zip_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="country" class="form-label">Ország</label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror" id="country" name="country" value="{{ old('country', $partner->country ?: 'Magyarország') }}">
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contact" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_person" class="form-label">Kapcsolattartó neve</label>
                                <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person', $partner->contact_person) }}">
                                @error('contact_person')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contact_position" class="form-label">Beosztás</label>
                                <input type="text" class="form-control @error('contact_position') is-invalid @enderror" id="contact_position" name="contact_position" value="{{ old('contact_position', $partner->contact_position) }}">
                                @error('contact_position')
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
                                    @include('admin.partners._contacts_list', ['contacts' => $partner->contacts])
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
                                    @include('admin.partners._documents_list', ['documents' => $partner->documents])
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="other" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="notes" class="form-label">Megjegyzések</label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="5">{{ old('notes', $partner->notes) }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
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
    const partnerId = {{ $partner->id }};
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

            fetch(`/admin/partners/${partnerId}/add-contact`, {
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

        fetch(`/admin/partners/contact/${contactId}/delete`, {
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

            fetch(`/admin/partners/${partnerId}/upload-document`, {
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

        fetch(`/admin/partners/document/${documentId}/delete`, {
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

