@extends('layouts.adm')

@section('content')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif

<h1>Alertas </h1>


<div class="input-group mb-3">
    <select class="custom-select" name="type" id="type">
        <option selected>Type</option>
        <option value="1">tocou</option>
        <option value="2">aproximou</option>
        <option value="3">pulou</option>
    </select>

    <select class="custom-select" name="cerca" id="cerca">
        <option selected>Cerca</option>
        <option value="1">cerca 1</option>
        <option value="2">cerca 2</option>
        <option value="3">cerca 3</option>
    </select>

    <select class="custom-select" name="device" id="device">
        <option selected>Device</option>
        <option value="1">device 1</option>
        <option value="2">device 2</option>
        <option value="3">device 3</option>
    </select>

    <div class="input-group-prepend">
        <label class="input-group-text" for="dt1">dt1</label>
    </div>
    <input class="form-control" type="datetime-local" id="dt1" name="dt1">

    <div class="input-group-prepend">
        <label class="input-group-text" for="dt1">dt2</label>
    </div>
    <input class="form-control" type="datetime-local" id="dt1" name="dt1">


    <div class="input-group-append">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">Acao</button>
        <div class="dropdown-menu">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
            <div role="separator" class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div>

</div>

<br>


<table class="table table-striped">
    @forelse ($alerts as $alert)
    <tr>
        <td>{{ $alert->id }}</td>
        <td>
            @switch($alert->type)
            @case(1)
            <span class="badge badge-danger">tipo 1</span>
            @break

            @case(2)
            <span class="badge badge-success">tipo 2</span>
            @break
            @case(3)
            <span class="badge badge-warning">tipo 3</span>
            @break
            @case(4)
            <span class="badge badge-info">tipo 4</span>
            @break
            @case(5)
            <span class="badge badge-primary">tipo 5</span>
            @break

            @default
            <span class="badge badge-secondary">default</span>
            @endswitch


        </td>
        <td>{{ $alert->dt->format('l d/M H:i:s') }}</td>

        <td><a class="btn btn-sm btn-info"
                onclick="javascript:geocodeLatLng('{{$alert->lat}}','{{$alert->lng}}')">local</a> </td>

        <td>{{ $alert->dist }}</td>

        <td>{{ $alert->device->name ?? '' }}</td>
        <td>{{ $alert->fence->name ?? '' }}</td>

        <td class="">
            <button class="btn btn-primary" data-lat="{{ $alert->lat }}" data-lng="{{ $alert->lng }}"
                data-cerca="{{ $alert->fence->fence ?? false }}" data-toggle="modal" data-target="#modal">map</button>
        </td>
        <td class="">
            <form method="POST" action="{{ route('alert.destroy',['alert'=>$alert]) }}">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger">del</button>
            </form>
        </td>
    </tr>


    @empty


    @endforelse
</table>

<hr>

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">New message</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" class="mb-2" style="width:98%;height:600px; "></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Send message</button>
            </div>
        </div>
    </div>
</div>



@endsection


@section('js')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('API_GOOGLE') }}&callback=init" async defer></script>
<script>



    function geocodeLatLng(lat, lng) {
        var geocoder = new google.maps.Geocoder;
        //console.log(geocoder);
        var latlng = { lat: parseFloat(lat), lng: parseFloat(lng) };
        //console.log(latlng);

        geocoder.geocode({ 'location': latlng }, function (results, status) {
            console.log(results);
            console.log(status);
            if (status == 'OK') {
                if (results[0]) {
                    console.log(typeof (results[0].formatted_address));
                    console.log(results[0].formatted_address);
                    alert(results[0].formatted_address);
                    //return results[0].formatted_address;
                } else {
                    return 'No results found';
                }
            } else {
                return 'Geocoder failed due to: ' + status;
            }
        });
    }






    function init() {

        $('#modal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var lat = parseFloat(button.data('lat')) || -22.90278;
            var lng = parseFloat(button.data('lng')) || -43.2075;
            var cerca = button.data('cerca') || false;
            var modal = $(this)
            modal.find('.modal-title').text(' Detalhamento:' + lat + ' / ' + lng)

            var map = new google.maps.Map(document.getElementById('map'), {
                center: { lat: lat, lng: lng },
                zoom: 15,
                mapTypeControl: false,
                scaleControl: false,
                streetViewControl: false,
                rotateControl: false

            });
            const marker = new google.maps.Marker({
                map: map,
                position: { lat: lat, lng: lng }
            });
            //alert(cerca);
            if (cerca) {
                var fence = new GMapFence();
                for (let i = 0; i < cerca.length; i++) {
                    fence.addVertex(cerca[i]);
                }

                if (fence.isValid()) {
                    /*                 var bounds = new google.maps.LatLngBounds(
                                        marker1.getPosition(), marker2.getPosition()
                                        fence.getBounds()
                                    );
                                    map.fitBounds(bounds); */
                    var pl = new google.maps.Polygon({
                        path: fence.generatePath(),
                        strokeColor: "#0000FF",
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: "#0000FF",
                        fillOpacity: 0.1
                    });
                    pl.setMap(map);

                } else {
                    alert('cerca invalida');
                }
            }

        });
    }



</script>




@endsection
