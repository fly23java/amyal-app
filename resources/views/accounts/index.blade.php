@extends('layouts.dashbord-layout')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">{{ trans('main.accounts') }}</h4>
            <div>
                <a href="{{ route('accounts.account.create') }}" class="btn btn-secondary" title="{{ trans('accounts.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($accounts) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('accounts.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('accounts.name_arabic') }}</th>
                            <th>{{ trans('users.email') }}</th>
                          
                            <th>{{ trans('accounts.iban') }}</th>
                          
                            <th>{{ trans('accounts.type') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($accounts as $account)
                        <tr>
                            <td class="align-middle">{{ $account->name_arabic }}</td>
                            <td class="align-middle">{{ $account->email }}</td>
                          
                          
                            <td class="align-middle">{{ $account->iban }}</td>
                           
                            <td class="align-middle">{{ $account->type }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('accounts.account.destroy', $account->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('accounts.account.show', $account->id ) }}" class="btn btn-info" title="{{ trans('accounts.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('accounts.account.edit', $account->id ) }}" class="btn btn-primary" title="{{ trans('accounts.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('accounts.delete') }}" onclick="return confirm(&quot;{{ trans('accounts.confirm_delete') }}&quot;)">
                                            <span class="fa-regular fa-trash-can" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>

           
        </div>
        
        @endif
    
    </div>
@endsection