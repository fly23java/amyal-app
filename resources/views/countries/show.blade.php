@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('countries.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('countries.country.destroy', $country->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('countries.country.edit', $country->id ) }}" class="btn btn-primary" title="{{ trans('countries.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('countries.delete') }}" onclick="return confirm(&quot;{{ trans('countries.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('countries.country.index') }}" class="btn btn-primary" title="{{ trans('countries.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('countries.country.create') }}" class="btn btn-secondary" title="{{ trans('countries.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.alpha2_code') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->alpha2_code }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.alpha3_code') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->alpha3_code }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.phone_code') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->phone_code }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('countries.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $country->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection