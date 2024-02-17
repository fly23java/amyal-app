@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('drivers.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('drivers.driver.destroy', $driver->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('drivers.driver.edit', $driver->id ) }}" class="btn btn-primary" title="{{ trans('drivers.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('drivers.delete') }}" onclick="return confirm(&quot;{{ trans('drivers.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('drivers.driver.index') }}" class="btn btn-primary" title="{{ trans('drivers.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('drivers.driver.create') }}" class="btn btn-secondary" title="{{ trans('drivers.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.email') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->email }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.password') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->password }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.phone') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->phone }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.identity_number') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->identity_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.date_of_birth_hijri') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->date_of_birth_hijri }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.date_of_birth_gregorian') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->date_of_birth_gregorian }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.account_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($driver->Account)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.vehicle_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($driver->Vehicle)->owner_name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('drivers.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $driver->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection