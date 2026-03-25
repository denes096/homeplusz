@extends('admin.layouts.app')

@section('title', $title ?? 'Részletek')
@section('page-title', $pageTitle ?? 'Részletek')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle ?? 'Részletek' }}</h4>
            <div class="action-buttons">
                <a href="{{ route($indexRoute ?? 'admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Vissza
                </a>
                <a href="{{ route($editRoute ?? 'admin.dashboard', $item->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Szerkesztés
                </a>
            </div>
        </div>
        <div class="card-body">
            <dl class="row">
                @foreach($fields as $field)
                <dt class="col-sm-3">{{ $field['label'] ?? $field['name'] }}</dt>
                <dd class="col-sm-9">
                    @if(isset($field['type']) && $field['type'] === 'image')
                        @if($item->{$field['name']})
                            <img src="{{ asset('storage/' . $item->{$field['name']}) }}" alt="" style="max-width: 300px; max-height: 300px;">
                        @else
                            -
                        @endif
                    @elseif(isset($field['type']) && $field['type'] === 'boolean')
                        @if($item->{$field['name']})
                            <span class="badge bg-success">Igen</span>
                        @else
                            <span class="badge bg-secondary">Nem</span>
                        @endif
                    @elseif(isset($field['type']) && $field['type'] === 'color')
                        <span class="badge" style="background-color: {{ $item->{$field['name']} }}; color: {{ $item->{$field['name']} }}; padding: 10px;">{{ $item->{$field['name']} }}</span>
                    @elseif(isset($field['type']) && $field['type'] === 'relationship' && isset($field['relationship']))
                        @php
                            $relation = $item->{$field['relationship']};
                            $attribute = $field['attribute'] ?? 'name';
                        @endphp
                        {{ $relation ? $relation->{$attribute} : '-' }}
                    @elseif(isset($field['type']) && $field['type'] === 'custom' && isset($field['value']))
                        {!! $field['value']($item) !!}
                    @else
                        {!! $item->{$field['name']} ?? '-' !!}
                    @endif
                </dd>
                @endforeach
            </dl>
        </div>
    </div>
</div>
@endsection

