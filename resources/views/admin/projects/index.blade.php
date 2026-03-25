@extends('admin.layouts.app')

@section('title', $title)
@section('page-title', $pageTitle)

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>{{ $pageTitle }}</h4>
            <a href="{{ route($createRoute) }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Új projekt
            </a>
        </div>
        <div class="card-body">
            @if($projects->isEmpty())
                <div class="alert alert-info">Nincsenek projektek.</div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Projekt azonosító</th>
                                <th>Név</th>
                                <th>Összefoglaló</th>
                                <th>Referens</th>
                                <th>Partner</th>
                                <th>Műveletek</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                            <tr>
                                <td>{{ $project->project_code }}</td>
                                <td>{{ $project->name }}</td>
                                <td>{{ \Str::limit($project->title, 50) }}</td>
                                <td>{{ $project->user->name ?? '-' }}</td>
                                <td>{{ $project->partner->name ?? '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route($showRoute, $project->id) }}" class="btn btn-sm btn-info" title="Megtekintés">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route($editRoute, $project->id) }}" class="btn btn-sm btn-primary" title="Szerkesztés">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route($destroyRoute, $project->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törölni szeretné ezt a projektet?');">
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
                    {{ $projects->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

