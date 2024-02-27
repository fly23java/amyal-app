@extends('layouts.app')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Status Change' }}</h4>
        <div>
            <form method="POST" action="{!! route('status_changes.status_change.destroy', $statusChange->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('status_changes.status_change.edit', $statusChange->id ) }}" class="btn btn-primary" title="{{ trans('status_changes.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('status_changes.delete') }}" onclick="return confirm(&quot;{{ trans('status_changes.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('status_changes.status_change.index') }}" class="btn btn-primary" title="{{ trans('status_changes.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('status_changes.status_change.create') }}" class="btn btn-secondary" title="{{ trans('status_changes.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.shipment_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($statusChange->Shipment)->serial_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.status_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($statusChange->Status)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.user_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($statusChange->User)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $statusChange->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('status_changes.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $statusChange->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection