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
            <h4 class="m-0">{{ trans('main.cities') }}</h4>
            <div>
                <a href="{{ route('cities.city.create') }}" class="btn btn-secondary" title="{{ trans('cities.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($cities) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('cities.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped  zero-configuration">
                    <thead>
                        <tr>
                            <th>{{ trans('cities.region_id') }}</th>
                            <th>{{ trans('cities.name_arabic') }}</th>
                            <th>{{ trans('cities.name_english') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($cities as $city)
                        <tr>
                            <td class="align-middle">{{ optional($city->Region)->name_arabic }}</td>
                            <td class="align-middle">{{ $city->name_arabic }}</td>
                            <td class="align-middle">{{ $city->name_english }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('cities.city.destroy', $city->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('cities.city.show', $city->id ) }}" class="btn btn-info" title="{{ trans('cities.show') }}">
                                            <i data-feather='eye'></i>
                                        </a>
                                        <a href="{{ route('cities.city.edit', $city->id ) }}" class="btn btn-primary" title="{{ trans('cities.edit') }}">
                                            <i data-feather='edit'></i>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('cities.delete') }}" onclick="return confirm(&quot;{{ trans('cities.confirm_delete') }}&quot;)">
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