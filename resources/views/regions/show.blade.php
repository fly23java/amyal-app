@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Region' }}</h4>
        <div>
            <form method="POST" action="{!! route('regions.region.destroy', $region->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('regions.region.edit', $region->id ) }}" class="btn btn-primary" title="{{ trans('regions.edit') }}">
                    <i data-feather='edit'></i>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('regions.delete') }}" onclick="return confirm(&quot;{{ trans('regions.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('regions.region.index') }}" class="btn btn-primary" title="{{ trans('regions.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('regions.region.create') }}" class="btn btn-secondary" title="{{ trans('regions.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('regions.country_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($region->Country)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('regions.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $region->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('regions.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $region->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('regions.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $region->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('regions.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $region->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection