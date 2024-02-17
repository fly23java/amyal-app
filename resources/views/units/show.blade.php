@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('units.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('units.unit.destroy', $unit->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('units.unit.edit', $unit->id ) }}" class="btn btn-primary" title="{{ trans('units.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('units.delete') }}" onclick="return confirm(&quot;{{ trans('units.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('units.unit.index') }}" class="btn btn-primary" title="{{ trans('units.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('units.unit.create') }}" class="btn btn-secondary" title="{{ trans('units.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('units.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $unit->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('units.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $unit->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('units.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $unit->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('units.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $unit->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection