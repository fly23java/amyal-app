@extends('layouts.dashbord-layout')

@section('content')

<div class="card text-bg-theme">

     <div class="card-header d-flex justify-content-between align-items-center p-3">
        <h4 class="m-0">{{ trans('accounts.show') }}</h4>
        <div>
            <form method="POST" action="{!! route('accounts.account.destroy', $account->id) !!}" accept-charset="UTF-8">
                <input name="_method" value="DELETE" type="hidden">
                {{ csrf_field() }}

                <a href="{{ route('accounts.account.edit', $account->id ) }}" class="btn btn-primary" title="{{ trans('accounts.edit') }}">
                    <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                </a>

                <button type="submit" class="btn btn-danger" title="{{ trans('accounts.delete') }}" onclick="return confirm(&quot;{{ trans('accounts.confirm_delete') }}?&quot;)">
                    <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                </button>

                <a href="{{ route('accounts.account.index') }}" class="btn btn-primary" title="{{ trans('accounts.show_all') }}">
                    <span class="fa-solid fa-table-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('accounts.account.create') }}" class="btn btn-secondary" title="{{ trans('accounts.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>

            </form>
        </div>
    </div>

    <div class="card-body">
        <dl class="row">
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.name_arabic') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->name_arabic }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.name_english') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->name_english }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.cr_number') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->cr_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.bank') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->bank }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.iban') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->iban }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.account_number') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->account_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.tax_number') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->tax_number }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.tax_value') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->tax_value }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.type') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->type }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.created_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->created_at }}</dd>
            <dt class="text-lg-end col-lg-2 col-xl-3">{{ trans('accounts.updated_at') }}</dt>
            <dd class="col-lg-10 col-xl-9">{{ $account->updated_at }}</dd>

        </dl>

    </div>
</div>

@endsection