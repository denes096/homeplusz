@if($properties->isEmpty())
    <div class="alert alert-info">Nincs találat.</div>
@else
    <div class="row">
        @foreach($properties as $p)
            @php
                $isOffered = $p->isOfferedToCustomer(request()->route('id'), request()->route('searchId'));
            @endphp
            <div class="col-md-4 mb-3">
                <div class="card h-100 {{ $isOffered ? 'border-warning' : '' }}">
                    @if($isOffered)
                        <div class="card-header bg-warning text-dark">
                            <small><i class="la la-check-circle"></i> Már kiajánlva</small>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->property_code ?? $p->id }}</h5>
                        <p class="card-text">{{ $p->propertyType->name ?? '' }} - {{ $p->settlement->name ?? '' }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ url('/admin/property/'.$p->id.'/edit') }}" class="btn btn-sm btn-outline-primary">Megnyit</a>
                            @if($isOffered)
                                <button class="btn btn-sm btn-outline-success" disabled>
                                    <i class="la la-check"></i> Elküldve
                                </button>
                            @else
                                <button class="btn btn-sm btn-success send-single-offer" 
                                        data-property-id="{{ $p->id }}"
                                        data-customer-id="{{ request()->route('id') }}"
                                        data-search-id="{{ request()->route('searchId') }}">
                                    Kiajánl
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{ $properties->links() }}
@endif


