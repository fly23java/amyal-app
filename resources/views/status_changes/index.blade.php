@extends('layouts.app')

@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success alert-dismissible" role="alert">
            {!! session('success_message') !!}

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card text-bg-theme">

        <div class="card-header d-flex justify-content-between align-items-center p-3">
            <h4 class="m-0">Status Changes</h4>
            <div>
                <a href="{{ route('status_changes.status_change.create') }}" class="btn btn-secondary" title="{{ trans('status_changes.create') }}">
                    <span class="fa-solid fa-plus" aria-hidden="true"></span>
                </a>
            </div>
        </div>
        
        @if(count($statusChanges) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('status_changes.none_available') }}</h4>
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

                <table class="table table-striped ">
                    <thead>
                        <tr>
                            <th>{{ trans('status_changes.shipment_id') }}</th>
                            <th>{{ trans('status_changes.status_id') }}</th>
                            <th>{{ trans('status_changes.user_id') }}</th>

                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($statusChanges as $statusChange)
                        <tr>
                            <td class="align-middle">{{ optional($statusChange->Shipment)->serial_number }}</td>
                            <td class="align-middle">{{ optional($statusChange->Status)->name_arabic }}</td>
                            <td class="align-middle">{{ optional($statusChange->User)->name }}</td>

                            <td class="text-end">

                                <form method="POST" action="{!! route('status_changes.status_change.destroy', $statusChange->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('status_changes.status_change.show', $statusChange->id ) }}" class="btn btn-info" title="{{ trans('status_changes.show') }}">
                                            <span class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('status_changes.status_change.edit', $statusChange->id ) }}" class="btn btn-primary" title="{{ trans('status_changes.edit') }}">
                                            <span class="fa-regular fa-pen-to-square" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="{{ trans('status_changes.delete') }}" onclick="return confirm(&quot;{{ trans('status_changes.confirm_delete') }}&quot;)">
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

            {!! $statusChanges->links('pagination') !!}
        </div>
        
        @endif
    
    </div>
@endsection