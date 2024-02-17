@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('vehicles.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('vehicles.vehicle.destroy', $vehicle->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('vehicles.vehicle.edit', $vehicle->id ) }}" class="btn btn-primary" title="{{ trans('vehicles.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('vehicles.delete') }}" onclick="return confirm(&quot;{{ trans('vehicles.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('vehicles.vehicle.index') }}" class="btn btn-primary" title="{{ trans('vehicles.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('vehicles.vehicle.create') }}" class="btn btn-secondary" title="{{ trans('vehicles.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.owner_name') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->owner_name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.sequence_number') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->sequence_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.plate') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->plate }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.right_letter') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->right_letter }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.middle_letter') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->middle_letter }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.left_letter') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->left_letter }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.plate_type') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->plate_type }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.vehicle_type_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($vehicle->VehicleType)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.account_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($vehicle->Account)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('vehicles.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $vehicle->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection