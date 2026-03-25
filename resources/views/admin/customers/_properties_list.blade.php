@if($properties->isEmpty())
    <div class="alert alert-info">Nincs találat.</div>
@else
    <div class="row">
        @foreach($properties as $p)
            @php
                $customerId = request()->route('id');
                $searchId = request()->route('searchId');
                $isOffered = $p->isOfferedToCustomer($customerId, $searchId);
            @endphp
            <div class="col-md-4 mb-3">
                <div class="card h-100 {{ $isOffered ? 'border-warning' : '' }}">
                    @if($isOffered)
                        <div class="card-header bg-warning text-dark">
                            <small><i class="fas fa-check-circle"></i> Már kiajánlva</small>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $p->property_code ?? $p->id }}</h5>
                        <p class="card-text">{{ $p->propertyType->name ?? '' }} - {{ $p->settlement->name ?? '' }}</p>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.properties.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">Megnyit</a>
                            @if($isOffered)
                                <button class="btn btn-sm btn-outline-success" disabled>
                                    <i class="fas fa-check"></i> Elküldve
                                </button>
                            @else
                                <button class="btn btn-sm btn-success send-single-offer" 
                                        data-property-id="{{ $p->id }}"
                                        data-customer-id="{{ $customerId }}"
                                        data-search-id="{{ $searchId }}">
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

