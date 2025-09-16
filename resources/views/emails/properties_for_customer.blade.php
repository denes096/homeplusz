<p>Kedves {{ $customer->name_0 }},</p>
<p>Az alábbi ingatlanokat találtuk az Ön számára:</p>

@foreach($properties as $p)
    <div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;">
        <h3 style="margin-top: 0; color: #333;">{{ $p->property_code ?? $p->id }}</h3>
        <p><strong>Típus:</strong> {{ $p->propertyType->name ?? 'N/A' }}</p>
        <p><strong>Település:</strong> {{ $p->settlement->name ?? 'N/A' }}</p>
        @if($p->price)
            <p><strong>Ár:</strong> {{ number_format($p->price, 0, ',', ' ') }} Ft</p>
        @endif
        <p>
            <a href="{{ url('/ingatlan/' . $p->id) }}" 
               style="background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Ingatlan részletei megtekintése
            </a>
        </p>
    </div>
@endforeach

<p>Ha bármilyen kérdése van, kérjük, keressen minket bizalommal!</p>
<p>Köszönettel,<br/>OtthonPlusz csapata</p>


