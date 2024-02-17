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
            <h4 class="m-0">Statuses</h4>
            <div>
                <a href="{{ route('statuses.status.create') }}" class="btn btn-secondary" title="{{ trans('statuses.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($statuses) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('statuses.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('statuses.name_arabic') }}</th>
                            <th>{{ trans('statuses.name_english') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($statuses as $status)
                        <tr>
                            <td class="align-middle">{{ $status->name_arabic }}</td>
                            <td class="align-middle">{{ $status->name_english }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('statuses.status.destroy', $status->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('statuses.status.show', $status->id ) }}" class="btn btn-info" title="{{ trans('statuses.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('statuses.status.edit', $status->id ) }}" class="btn btn-primary" title="{{ trans('statuses.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('statuses.delete') }}" onclick="return confirm(&quot;{{ trans('statuses.confirm_delete') }}&quot;)">
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