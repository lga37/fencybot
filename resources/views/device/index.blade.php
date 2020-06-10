@extends('layouts.adm')


@section('content')

@if (session('status'))
<div class="alert alert-success" role="alert">
    {{ session('status') }}
</div>
@endif




<h1>Devices para usuario </h1>
<table class="table table-striped">
    <tr>
        <th>add</th>
        <th>
            <form method="POST" action="{{ route('device.store') }}">
                @csrf
                <select name="fences_id[]" class="form-control rounded-lg border border-secondary selectpicker"
                    multiple>
                    <option selected disabled>Selecione</option>
                    @forelse ($fences as $fence)
                    <option value="{{ $fence->id }}">{{ $fence->name }} </option>
                    @empty
                    @endforelse
                </select>
        </th>

        <th class="">
            <input type="text" name="name" class="form-control">
        </th>
        <th><input type="text" name="tel" class="form-control"></th>
        <th><input type="text" name="t" value="15" class="form-control"></th>
        <th><input type="text" name="d" value="1" class="form-control"></th>
        <th><input type="text" name="r" value="10" class="form-control"></th>
        <th colspan="4" class="">
            <button class="btn btn-block btn-outline-success">add</button>
            </form>
        </th>
    </tr>
    <tr>
        <th>id</th>
        <th>cerca assoc</th>
        <th>name</th>
        <th>tel</th>
        <th>t</th>
        <th>d</th>
        <th>r</th>
        <th>upd</th>
        <th>del</th>
        <th>get</th>
        <th>map</th>
    </tr>

    @forelse ($devices as $device)

    <tr class="">
        <td class="">{{ $device->id }}</td>
        <td class="">
            <form method="POST" action="{{ route('device.update',['device'=>$device->id]) }}">
                @method('PUT')
                @csrf
                <select name="fences_id[]" class="form-control border border-info selectpicker" multiple>
                    <?php
                    foreach ($fences as $fence):
                        $sel = '';
                        if(isset($device->fences)){
                            $sel = in_array($fence->id,$device->fences->pluck('id')->toArray())? 'selected':'';
                        }
                        echo sprintf("<option %s value='%d'>%s</option>",$sel,$fence->id,$fence->name);
                    endforeach;
                    ?>
                </select>
        </td>
        <td class="">
            <input type="text" name="name" value="{{ $device->name }}" class="form-control">
        </td>
        <td class="">
            <input type="text" name="tel" value="{{ $device->tel }}" class="form-control">
        </td>
        <td class="">
            <input type="text" name="t" value="{{ $device->t }}" class="form-control">
        </td>
        <td class="">
            <input type="text" name="d" value="{{ $device->d }}" class="form-control">
        </td>
        <td class="">
            <input type="text" name="r" value="{{ $device->r }}" class="form-control">
        </td>

        <td class="">
            <button class="btn btn-info">upd</button>
            </form>
        </td>

        <td class="">
            <form method="POST" action="{{ route('device.destroy',['device'=>$device]) }}">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger">del</button>
            </form>
        </td>
        <td>
            <a target="_blank" href="{{ route('fence.get',['tel'=>$device->tel]) }}" class="btn btn-warning">get</a>
        </td>
        <td>
            <a target="_blank" href="{{ route('device.show',['device'=>$device]) }}" class="btn btn-primary">map</a>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9">Nenhum Registro</td>
    </tr>
    @endforelse

</table>


@forelse ($devices as $device)

@if ($loop->first)

<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        @endif
        <div class="col-auto mb-3">
            <div class="card" style="width: 20rem;">
                <div class="card-header">{{ $device->created_at }}</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $device->name }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $device->tel }}</h6>

                    {{ $device->t }}
                    {{ $device->d }}
                    {{ $device->r }}

                    <form method="POST" action="{{ route('device.update',['device'=>$device->id]) }}">
                        @method('PUT')
                        @csrf
                        <select name="fences_id[]" class="form-control border border-info selectpicker" multiple>
                            <?php
                            foreach ($fences as $fence):
                                $sel = '';
                                if(isset($device->fences)){
                                    $sel = in_array($fence->id,$device->fences->pluck('id')->toArray())? 'selected':'';
                                }
                                echo sprintf("<option %s value='%d'>%s</option>",$sel,$fence->id,$fence->name);
                            endforeach;
                            ?>
                        </select>
                    </form>

                    <a href="#" class="card-link">Card link</a>
                    <a href="#" class="card-link">Another link</a>
                </div>
                <div class="card-footer">
                    <a class="text-red" href="{{ route('fence.index') }}">
                        <span data-feather="map-pin"></span>
                        Fences
                    </a>

                        <form method="POST" action="{{ route('device.destroy',['device'=>$device]) }}">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-sm btn-danger">del</button>

                            <a target="_blank" href="{{ route('fence.get',['tel'=>$device->tel]) }}" class="btn btn-sm btn-warning">get</a>

                            <a target="_blank" href="{{ route('device.show',['device'=>$device]) }}" class="btn btn-sm btn-primary">map</a>

                        </form>


                </div>
            </div>
        </div>


        @if ($loop->last)
    </div>
</div>
@endif



@empty

sem regs

@endforelse



<div class="card border-success mb-3">
    <div class="card-header bg-transparent border-success">Header</div>
    <div class="card-body text-success">
        <h5 class="card-title">Success card title</h5>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
            content.</p>
    </div>
    <div class="card-footer bg-transparent border-success">Footer</div>
</div>









@endsection
