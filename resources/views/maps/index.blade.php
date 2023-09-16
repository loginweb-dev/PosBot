@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>
     <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
     <style>
        #map { height: 600px; }
     </style>
@stop

@section('title', "Rutas & Mapas")
@section('content')
    <section class="content">
        <div id="map"></div>
    </section>
</div>

@stop

@section('javascript')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
crossorigin=""></script>
<script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
<script>
    var map = L.map('map').setView([-14.826273337955922, -64.88904732091238], 14);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    // maxZoom: 12,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

L.Routing.control({
  waypoints: [
    L.latLng(-14.826273337955922, -64.88904732091238),
    L.latLng(-14.840461264840306, -64.90029114004429)
  ]
}).addTo(map);
</script>
@stop