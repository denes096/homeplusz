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
            <form action="{{ route($updateRoute, $search->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="customer_id" class="form-label">Vevő <span class="text-danger">*</span></label>
                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                            <option value="">Válassz vevőt...</option>
                            @foreach($customers as $id => $name)
                                <option value="{{ $id }}" {{ (old('customer_id', $search->customer_id) == $id) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('customer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="search" class="form-label">Keresési paraméterek (JSON) <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('search') is-invalid @enderror" 
                                  id="search" 
                                  name="search" 
                                  rows="10" 
                                  required
                                  placeholder='{"p[price_min]": 100000, "p[price_max]": 500000, "p[property_types]": [1,2]}'>{{ old('search', $search->search) }}</textarea>
                        <small class="form-text text-muted">
                            Adja meg a keresési paramétereket JSON formátumban. Például: {"p[price_min]": 100000, "p[price_max]": 500000, "p[property_types]": [1,2]}
                        </small>
                        @error('search')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Mentés
                    </button>
                    <a href="{{ route($indexRoute) }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Mégse
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

