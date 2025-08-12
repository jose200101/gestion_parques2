@extends('layouts.app') 

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Localización en el mapa</h3>
        </div>
        <div class="card-body">
            <div id="map" style="height: 500px;"></div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Solo necesitas el CSS de tu archivo local --}}
    <link rel="stylesheet" href="{{ asset('css/mapa-interactivo.css') }}">
@endpush

@push('js')
    {{-- Reemplaza con tu clave de API --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOqbIiWQnnHaQfH5aUvL0oFwh0JOUaIzw&callback=initMap" async defer></script>
    <script src="{{ asset('js/mapa-interactivo.js') }}"></script>
@endpush