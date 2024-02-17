@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('cities.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('cities.city.destroy', $city->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('cities.city.edit', $city->id ) }}" class="btn btn-primary" title="{{ trans('cities.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('cities.delete') }}" onclick="return confirm(&quot;{{ trans('cities.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('cities.city.index') }}" class="btn btn-primary" title="{{ trans('cities.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('cities.city.create') }}" class="btn btn-secondary" title="{{ trans('cities.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('cities.region_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($city->Region)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('cities.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $city->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('cities.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $city->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('cities.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $city->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('cities.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $city->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection