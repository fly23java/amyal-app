@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ isset($title) ? $title : 'Status' }}</h4>
        <div>
            <form method="POST" action="{!! route('statuses.status.destroy', $status->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('statuses.status.edit', $status->id ) }}" class="btn btn-primary" title="{{ trans('statuses.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('statuses.delete') }}" onclick="return confirm(&quot;{{ trans('statuses.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('statuses.status.index') }}" class="btn btn-primary" title="{{ trans('statuses.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('statuses.status.create') }}" class="btn btn-secondary" title="{{ trans('statuses.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.message_text_in_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->message_text_in_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.message_text_in_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->message_text_in_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.confirm_sending_the_message') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ ($status->confirm_sending_the_message) ? 'Yes' : 'No' }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.parent_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($status->ParentStatus)->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('statuses.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $status->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection