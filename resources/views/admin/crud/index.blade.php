@extends('admin.layouts.app')

@section('title', $title ?? 'Lista')
@section('page-title', $pageTitle ?? 'Lista')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle ?? 'Lista' }}</h4>
            <a href="{{ route($createRoute ?? 'admin.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Új hozzáadása
            </a>
        </div>
        <div class="card-body">
            @if(isset($items) && $items->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                @foreach($columns as $column)
                                <th>{{ $column['label'] ?? $column['name'] }}</th>
                                @endforeach
                                <th class="text-end">Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                @foreach($columns as $column)
                                <td>
                                    @if(isset($column['type']) && $column['type'] === 'image')
                                        <img src="{{ $item->{$column['name']} }}" alt="" style="max-width: 100px; max-height: 100px;">
                                    @elseif(isset($column['type']) && $column['type'] === 'boolean')
                                        @if($item->{$column['name']})
                                            <span class="badge bg-success">Igen</span>
                                        @else
                                            <span class="badge bg-secondary">Nem</span>
                                        @endif
                                    @elseif(isset($column['type']) && $column['type'] === 'color')
                                        <span class="badge" style="background-color: {{ $item->{$column['name']} }}; color: {{ $item->{$column['name']} }};">{{ $item->{$column['name']} }}</span>
                                    @elseif(isset($column['type']) && $column['type'] === 'relationship' && isset($column['relationship']))
                                        @php
                                            $relation = $item->{$column['relationship']};
                                            $attribute = $column['attribute'] ?? 'name';
                                        @endphp
                                        {{ $relation ? $relation->{$attribute} : '-' }}
                                    @elseif(isset($column['type']) && $column['type'] === 'custom' && isset($column['value']))
                                        {!! $column['value']($item) !!}
                                    @else
                                        {{ $item->{$column['name']} ?? '-' }}
                                    @endif
                                </td>
                                @endforeach
                                <td class="text-end">
                                    <div class="action-buttons">
                                        @if(isset($showRoute))
                                        <a href="{{ route($showRoute, $item->id) }}" class="btn btn-sm btn-info" title="Megtekintés">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @endif
                                        <a href="{{ route($editRoute ?? 'admin.dashboard', $item->id) }}" class="btn btn-sm btn-primary" title="Szerkesztés">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route($destroyRoute ?? 'admin.dashboard', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törölni szeretnéd?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Törlés">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if(method_exists($items, 'links'))
                <div class="d-flex justify-content-center">
                    {{ $items->links() }}
                </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <h4>Nincs találat</h4>
                    <p>Még nincsenek elemek ebben a listában.</p>
                    <a href="{{ route($createRoute ?? 'admin.dashboard') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Első elem hozzáadása
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

