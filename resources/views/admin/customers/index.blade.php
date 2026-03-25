@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <a href="{{ route($createRoute) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Új vevő
            </a>
        </div>
        <div class="card-body">
            @if($customers->isEmpty())
                <div class="alert alert-info">Nincsenek vevők.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Név</th>
                                <th>Email</th>
                                <th>Telefon</th>
                                <th>Referens</th>
                                <th>Státusz</th>
                                <th>Kategória</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->name_0 }}</td>
                                <td>{{ $customer->email ?: '-' }}</td>
                                <td>{{ $customer->phone_0 ?: '-' }}</td>
                                <td>{{ $customer->referens->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $customer->status === 'Aktív' ? 'success' : ($customer->status === 'Felfüggesztve' ? 'warning' : 'secondary') }}">
                                        {{ $customer->status }}
                                    </span>
                                </td>
                                <td>{{ $customer->kategoria === 'maganszemely' ? 'Magánszemély' : 'Beruházó' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route($showRoute, $customer->id) }}" class="btn btn-sm btn-info" title="Megtekintés">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route($editRoute, $customer->id) }}" class="btn btn-sm btn-primary" title="Szerkesztés">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route($destroyRoute, $customer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törölni szeretné ezt a vevőt?');">
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
                
                <div class="mt-3">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

