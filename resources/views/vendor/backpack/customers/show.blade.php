@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>Vevő: {{ $customer->name_0 }}</h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" id="customerTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="data-tab" data-bs-toggle="tab" data-bs-target="#data" type="button" role="tab">Alapadatok</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="searches-tab" data-bs-toggle="tab" data-bs-target="#searches" type="button" role="tab">Keresési paraméterek</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="data" role="tabpanel">
                    <div class="card p-3">
                        <p><strong>Név:</strong> {{ $customer->name_0 }}</p>
                        <p><strong>Email:</strong> {{ $customer->email }}</p>
                        <p><strong>Telefon:</strong> {{ $customer->phone_0 }}</p>
                        <p><strong>Megjegyzés:</strong> {{ $customer->note }}</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="searches" role="tabpanel">
                    <div class="card p-3">
                        @if($searches->isEmpty())
                            <div class="alert alert-info">Nincsenek mentett keresési paraméterek.</div>
                        @endif

                        @foreach($searches as $s)
                            <div class="mb-3 border p-2">
                                <div class="d-flex justify-content-between">
                                    <div><strong>Keresés #{{ $s->id }}</strong></div>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-primary execute-search" data-id="{{ $s->id }}">Futtatás</a>
                                        <a href="{{ url(config('backpack.base.route_prefix').'/customer-search/'.$s->id.'/edit') }}" class="btn btn-sm btn-secondary">Szerkeszt</a>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <pre style="white-space:pre-wrap">{{ json_encode($s->search_array, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</pre>
                                </div>
                            </div>
                        @endforeach

                        <div id="properties-result"></div>

                        <form id="send-email-form" method="POST" action="{{ url(config('backpack.base.route_prefix').'/customers/'.$customer->id.'/send-property-email') }}">
                            @csrf
                            <input type="hidden" name="property_ids" id="property_ids_input">
                            <button type="submit" class="btn btn-success mt-3">Küldés emailben</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('after_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.execute-search').forEach(function(btn){
                btn.addEventListener('click', function(e){
                    e.preventDefault();
                    var id = this.dataset.id;
                    fetch('{{ url(config('backpack.base.route_prefix')) }}' + '/customers/{{ $customer->id }}/execute-search/' + id)
                        .then(r=>r.text())
                        .then(html=>{
                            document.getElementById('properties-result').innerHTML = html;
                        });
                });
            });

            window.selectPropertyForEmail = function(id){
                var el = document.getElementById('property_ids_input');
                var arr = el.value ? el.value.split(',').filter(Boolean) : [];
                if (arr.includes(String(id))) {
                    arr = arr.filter(function(x){ return x != id; });
                } else {
                    arr.push(id);
                }
                el.value = arr.join(',');
            }
        });
    </script>
    @endpush

@endsection


