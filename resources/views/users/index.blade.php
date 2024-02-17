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
            <h4 class="m-0">{{ trans('main.users') }}</h4>
            <div>
                <a href="{{ route('users.user.create') }}" class="btn btn-secondary" title="{{ trans('users.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($users) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('users.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('users.name') }}</th>
                            <th>{{ trans('users.email') }}</th>
                            <th>{{ trans('users.birth_date') }}</th>
                            <th>{{ trans('users.account_id') }}</th>
                            <th>{{ trans('users.type') }}</th>
                            <th>{{ trans('users.status') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="align-middle">{{ $user->name }}</td>
                            <td class="align-middle">{{ $user->email }}</td>
                            <td class="align-middle">{{ $user->birth_date }}</td>
                            <td class="align-middle">{{ optional($user->Account)->name_arabic }}</td>
                            <td class="align-middle">{{ $user->type }}</td>
                            <td class="align-middle">{{ $user->status }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('users.user.destroy', $user->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.user.show', $user->id ) }}" class="btn btn-info" title="{{ trans('users.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('users.user.edit', $user->id ) }}" class="btn btn-primary" title="{{ trans('users.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('users.delete') }}" onclick="return confirm(&quot;{{ trans('users.confirm_delete') }}&quot;)">
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