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
            <h4 class="m-0">{{ trans('main.shipment_not_active') }}</h4>
           
        </div>
        
        @if(count($shipments) == 0)
            <div class="card-body text-center">
                <h4>{{ trans('shipments.none_available') }}</h4>

            
            </div>
        @else
        <div class="card-body p-0">
            <div class="table-responsive">

            
                        <!-- Basic Tabs starts -->
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                
                                <div class="card-body">
                                    <ul class="nav nav-tabs" role="tablist">

                                   
                                    @foreach ( $statuses  as   $stats )
                                        

                                        <li class="nav-item">
                                        
                                            <a class="nav-link tab-shipment2 " id="home-tab{{ $stats->id }}" data-id="{{ $stats->id }}" data-toggle="tab" href="#home{{ $stats->id }}" aria-controls="home{{ $stats->id }}" role="tab" aria-selected="true">
                                                    <span class="badge badge-light-success badge-pill ml-auto mr-1">
                                                      
                                                                {{ $stats->shipment_count }}
                                                      
                                                    </span>
                                            {{ $stats->name_arabic }} 
                                            
                                            </a>
                                        </li>
                                     @endforeach
                                        
                                   
                                      
                                    </ul>
                                    <div class="tab-content">
                                       
                                        @foreach ( $statuses as   $stats )
                                        <div class="tab-pane" id="home{{ $stats->id }}" aria-labelledby="home-tab{{ $stats->id }}" role="tabpanel">
                                                
                                        </div>
                                        @endforeach
                                    
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Basic Tabs ends -->

                  
                    
                
                            

             

            </div>

        
        </div>
        
        @endif

            <!-- modals shipment edit -->
              
        
        
            @include('shipments.modals-statuses-update')          
           
               






                        
    
    </div>



@endsection


@section('script')  
<script>
  


    $(document).ready(function () {
    // Handle tab click event

    $('.tab-shipment2').on('click', function (e) {
        e.preventDefault();

        // Get the tab content ID based on the data-id attribute
        var id = $(this).data('id');

        var data = {
            'id': id
        };

        $.ajax({
            type: "GET",
            url: "{{ route('shipment_not_actives.shipment_not_active.retrunShipmentInTabsByStatus') }}",
            data: data,
            dataType: "json",
            success: function (response) {
                $('#home' + id).html("");
                var table = $('.zero-configuration3').DataTable();
                table.destroy();

                $('#home' + id).append(response.data);

                $('.zero-configuration3').DataTable({
                    "drawCallback": function () {
                        $('.previous').addClass('btn btn-sm btn-dark');
                        $('.paginate_button').addClass('btn btn-sm btn-primary');
                        $('.next').addClass('btn btn-sm btn-dark');
                        $('div.dataTables_filter input').addClass('form-control');
                        $('.dataTables_length').addClass('d-none');
                    },
                    order: [[3, 'desc']]
                });
            }
        });
    });
});

</script>

@endsection