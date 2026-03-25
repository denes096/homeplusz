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
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> <strong>Először mentsd el a Vevőt, utána tudod csak szerkeszteni a kapcsolattartókat, dokumentumokat és keresési paramétereket!</strong>
            </div>

            <form action="{{ route($storeRoute) }}" method="POST">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label">Státusz <span class="text-danger">*</span></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="Aktív" {{ old('status', 'Aktív') == 'Aktív' ? 'selected' : '' }}>Aktív</option>
                            <option value="Felfüggesztve" {{ old('status') == 'Felfüggesztve' ? 'selected' : '' }}>Felfüggesztve</option>
                            <option value="Archív" {{ old('status') == 'Archív' ? 'selected' : '' }}>Archív</option>
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
                            <option value="{{ $id }}" {{ old('refId') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('refId')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kategoria" class="form-label">Kategória <span class="text-danger">*</span></label>
                        <select class="form-select @error('kategoria') is-invalid @enderror" id="kategoria" name="kategoria" required>
                            <option value="maganszemely" {{ old('kategoria', 'maganszemely') == 'maganszemely' ? 'selected' : '' }}>Magánszemély</option>
                            <option value="beruhazo" {{ old('kategoria') == 'beruhazo' ? 'selected' : '' }}>Beruházó</option>
                        </select>
                        @error('kategoria')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name_0" class="form-label">Név <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name_0') is-invalid @enderror" id="name_0" name="name_0" value="{{ old('name_0') }}" required>
                        @error('name_0')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone_0" class="form-label">Telefonszám</label>
                        <input type="text" class="form-control @error('phone_0') is-invalid @enderror" id="phone_0" name="phone_0" value="{{ old('phone_0') }}">
                        @error('phone_0')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="azonosito1_0" class="form-label">Azonosító</label>
                        <input type="text" class="form-control @error('azonosito1_0') is-invalid @enderror" id="azonosito1_0" name="azonosito1_0" value="{{ old('azonosito1_0') }}">
                        @error('azonosito1_0')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email cím</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Cím</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}">
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="note" class="form-label">Megjegyzés</label>
                        <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="3">{{ old('note') }}</textarea>
                        @error('note')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
@endsection

