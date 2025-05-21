@extends(backpack_view('blank'))

@php
    $user = \Illuminate\Support\Facades\Auth::user();
    $widgets['before_content'][] = [
        'type'        => 'jumbotron',
        'heading'     => "Üdvözöllek $user->name!",
        'heading_class' => 'display-3 '.(backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
        'content'     => '',
        'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
        'button_link' => backpack_url('logout'),
        'button_text' => trans('Kejelentkezés'),
    ];

@endphp

@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .search-container {
            position: relative;
        }

        .search-input {
            height: 50px;
            border-radius: 30px;
            padding-left: 35px;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .search-icon {
            position: absolute;
            top: 50%;
            left: 15px;
            transform: translateY(-50%);
            color: #888;
        }
    </style>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h3 class="text-center w-100">Ingatlan/Projekt keresése kód alapján</h3>
                </div>
                <div class="card-body">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <div class="search-container">
                                    <input type="text" id="property-search" class="form-control search-input">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <a href="/admin/property" class="btn btn-primary w-100" style="height: 90px">Ingatlan karbantartás</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <a href="/admin/property/create" class="btn btn-primary w-100" style="height: 90px">Új ingatlan felvitele</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <a href="/admin/property-image-downloader" class="btn btn-primary w-100" style="height: 90px">Képletöltés</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col"></div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <a href="" class="btn btn-primary w-100" style="height: 90px">Új vevő felvitele</a>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <a href="" class="btn btn-primary w-100" style="height: 90px">Vevő karbantartás</a>
                </div>
            </div>
        </div>
        <div class="col"></div>
    </div>
@endsection


