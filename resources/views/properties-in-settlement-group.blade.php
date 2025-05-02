@extends('layouts/homeplus')

@section('content')

    <!--
=====================================================
    Property Listing
=====================================================
-->
    @include('includes/property-lists-in-settlement-group', ['properties' => $propertiesInTheArea, 'settlementGroup' => $settlementGroup])


    <div>
        <hr>
    </div>
    @include('includes/areas')

    <!--
=============================================
    NUMBERS
==============================================
-->
@endsection
