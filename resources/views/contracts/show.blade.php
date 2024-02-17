@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('contracts.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('contracts.contract.destroy', $contract->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('contracts.contract.edit', $contract->id ) }}" class="btn btn-primary" title="{{ trans('contracts.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('contracts.delete') }}" onclick="return confirm(&quot;{{ trans('contracts.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('contracts.contract.index') }}" class="btn btn-primary" title="{{ trans('contracts.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('contracts.contract.create') }}" class="btn btn-secondary" title="{{ trans('contracts.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.sender_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contract->User)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.receiver_id') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ optional($contract->User)->name }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('contracts.description') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $contract->description }}</dd>

        </dl>

    </div>
</div>

@endsection