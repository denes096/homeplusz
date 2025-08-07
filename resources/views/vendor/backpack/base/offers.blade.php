@extends(backpack_view('blank'))

@section('header')
    <section class="content-header">
        <h1>
            Vevő rögzítése
        </h1>
    </section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="POST" action="">
                @csrf

                    <div class="card">
                        <div class="card-body">
                            <div class="row g-1">
                                <div class="col-md-4">
                                    <label class="form-label">Státusz</label>
                                    <select name="status" class="form-select">
                                        <option value="Aktív">Aktív</option>
                                        <option value="Felfüggesztve">Felfüggesztve</option>
                                        <option value="Archív">Archív</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Referens</label>
                                    <select name="refId" class="form-select">
                                        <option value="">-- válassz --</option>
                                        @foreach(\App\Models\User::all() as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Kategória</label>
                                    <select name="kategoria" class="form-select">
                                        <option value="1">*</option>
                                        <option value="2">**</option>
                                        <option value="3">***</option>
                                        <option value="4">****</option>
                                        <option value="5">*****</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">EKód</label>
                                    <input type="text" name="ekod" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Név</label>
                                    <input type="text" name="name_0" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Telefon</label>
                                    <input type="text" name="phone_0" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Azonosító</label>
                                    <input type="text" name="azonosito1_0" class="form-control">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label">Megjegyzés</label>
                                    <textarea name="note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                <div class="card mt-2">
                    <div class="card-body">
                        <div class="row g-1">
                            <div class="col-12 text-center">
                                <div class="h4">Alapadatok</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="">Típus</label>
                                <select class="form-select" name="ad_type" id="">
                                    <option>Eladó</option>
                                    <option>Kiadó</option>
                                    <option>Minden</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Min ár</label>
                                <input type="number" name="min_ar" class="form-control">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">Max ár</label>
                                <input type="number" name="max_ar" class="form-control">
                            </div>


                        </div>

                        <div class="row g-1">

                            <div class="col-md-3">
                                <label class="form-label" for="">Ingatlantípus</label>
                                <select class="form-select multiselect" multiple name="property_types[]" id="">
                                    @foreach(\App\Models\PropertyType::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="">Ingatlanaltípus</label>
                                <select class="form-select multiselect" multiple name="property_subtypes[]" id="">
                                    @foreach(\App\Models\PropertySubtype::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="">Település</label>
                                <select class="form-select multiselect" multiple name="settlements[]" id="">
                                    @foreach(\App\Models\Settlement::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="">Település rész</label>
                                <select class="form-select multiselect" multiple name="settlement_parts[]" id="">
                                    @foreach(\App\Models\SettlementPart::all() as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <?php
                                $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();
                                foreach ($propAttrsCats as $propAttrCat) {
                                    echo "<hr>";
                                    echo "<div class='text-center'><strong>$propAttrCat->description</strong></div>";
                                    /**  @var $propAttrs \App\Models\PropertyAttribute[]  */
                                  $propAttrs = $propAttrCat->propertyAttributes;
                                  foreach ($propAttrs as $propAttr) {
                                      if ($propAttr->type == 'number') { ?>
                                          <div class="col-md-3">
                                              <div>
                                                  <label for="">{{ $propAttr->label }}</label>
                                              </div>
                                              <div style="display: flex">
                                                  <div style="width: 40%">
                                                      <input  style="width: 100%;"  type="number" name="{{ $propAttr->name }}_min" id="">
                                                  </div>
                                                  &nbsp;-&nbsp;
                                                  <div style="width: 40%">
                                                      <input style="width: 100%;"  type="number" name="{{ $propAttr->name }}_max" id="">
                                                  </div>
                                              </div>
                                          </div>
                            <?php
                                      } else if ($propAttr->type == 'select' || $propAttr->$type == 'select_multiple') {
                            ?>
                                        <div class="col-md-3">
                                            <label class="form-label" for="">{{ $propAttr->label }}</label>
                                            <select class="form-select multiselect" multiple name="{{$propAttr->name}}[]" id="">
                                                @foreach(json_decode($propAttr->values, true) as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                            <?php } else if ($propAttr->type == 'checkbox') { ?>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input
                                                    type="checkbox"
                                                    class="form-check-input" name="{{ $propAttr->name }}" id="{{$propAttr->id}}">
                                                <label class="form-check-label" for="{{$propAttr->id}}">{{ $propAttr->label }}</label>
                                            </div>
                                        </div>
                            <?php
                                    }
                                  }
                                } ?>
                            <hr>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Mentés</button>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            jQuery('.multiselect').select2({
                width: '100%'
            });
        });
    </script>
@endsection
