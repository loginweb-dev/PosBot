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

  let id;
  let target;
  let options;

  target = {
    latitude: 0,
    longitude: 0,
  };

  options = {
    enableHighAccuracy: false,
    timeout: 5000,
    maximumAge: 0,
  };

    var map = L.map('map').setView([-14.835007100679588, -64.90413803621838], 14);

    var marker = L.marker([-14.835007100679588, -64.90413803621838]).addTo(map);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    if (navigator.geolocation) {
      navigator.geolocation.watchPosition(showPosition, error, options);
      id = navigator.geolocation.watchPosition(success, error, options);
    } else {
      alert("Geolocation is not supported by this browser.")
    }

  function showPosition(position) {
    marker.setLatLng([position.coords.latitude, position.coords.longitude]);
    map.panTo(new L.LatLng(position.coords.latitude, position.coords.longitude))
    var marker1 = L.marker([{{ $miscotizaciones[0]->shipping_details }}, {{ $miscotizaciones[0]->shipping_address }}]).addTo(map);
  }

  function success(pos) {
    const crd = pos.coords;
    map.panTo(new L.LatLng(crd.latitude, crd.longitude))
    marker.setLatLng([crd.latitude, crd.longitude])
  }

  function error(err) {
    console.error(`ERROR(${err.code}): ${err.message}`);
  }

  // L.Routing.control({
  //   waypoints: [
  //     L.latLng({{ $miscotizaciones[0]->shipping_details }}, {{ $miscotizaciones[0]->shipping_address }}),
  //     // L.latLng('{{ $miscotizaciones[0]->shipping_detail }}'),
  //     L.latLng(-14.825206082418994, -64.88854903867112)
  //   ]
  // }).addTo(map);
</script>
@stop