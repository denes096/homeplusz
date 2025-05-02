<div class="d-flex flex-wrap">
    @foreach($settlementGroups as $settlementGroup)
    <div class="col-sm-4 col-6 p-5">
        <a href="/kornyek/{{$settlementGroup->id}}-{{\Illuminate\Support\Str::slug($settlementGroup->settlement->name)}}-es-kornyeke" class="d-flex">
            <img src="" alt="" style="border: 2px solid black; width: 100px; height: 100px;">
            <div class="ps-4">
                <h5 class="m-0">{{$settlementGroup->settlement->name}}</h5>
                <p class="m-0">és környéke</p>
            </div>
        </a>
    </div>
    @endforeach
</div>
