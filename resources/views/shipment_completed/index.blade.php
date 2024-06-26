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
            <h4 class="m-0">{{ trans('main.finished_shipment') }}</h4>
           
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

                                   
                                  
                                        

                                        <li class="nav-item">
                                        
                                            <a class="nav-link active tab-shipmentsWithOutDeliveryDocumentOnly " id="tab-shipmentsWithOutDeliveryDocumentOnly" data-toggle="tab" href="#shipmentsWithOutDeliveryDocumentOnly" aria-controls="shipmentsWithOutDeliveryDocumentOnly" role="tab" aria-selected="true">
                                                    <span class="badge badge-light-success badge-pill ml-auto mr-1">
                                                      
                                                                {{ $shipmentsWithOutDeliveryDocumentOnly  }}
                                                      
                                                    </span>
                                                    {{ trans('main.shipment_without_delivery_document') }}
                                            
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                        
                                            <a class="nav-link tab-shipmentsWithDeliveryDocumentOnly " id="tab-shipmentsWithDeliveryDocumentOnly" data-id="shipmentsWithDeliveryDocumentOnly" data-toggle="tab" href="#shipmentsWithDeliveryDocumentOnly" aria-controls="shipmentsWithDeliveryDocumentOnly" role="tab" aria-selected="true">
                                                    <span class="badge badge-light-success badge-pill ml-auto mr-1">
                                                      
                                                                {{ $shipmentsWithDeliveryDocumentOnly }}
                                                      
                                                    </span>
                                                    {{ trans('main.shipment_with_delivery_document') }}
                                            
                                            </a>
                                        </li>
                                    
                                        
                                   
                                      
                                    </ul>
                                    <div class="tab-content">
                                       
                                       
                                        <div class="tab-pane" id="shipmentsWithOutDeliveryDocumentOnly" aria-labelledby="shipmentsWithOutDeliveryDocumentOnly-tab" role="tabpanel">
                                                
                                        </div>
                                        <div class="tab-pane" id="shipmentsWithDeliveryDocumentOnly" aria-labelledby="shipmentsWithDeliveryDocumentOnly-tab" role="tabpanel">
                                                
                                        </div>
                                      
                                    
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

<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.6/css/jquery.dataTables.min.css">
<!-- DataTables Buttons CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">

  
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script>
  


    $(document).ready(function () {
    // Handle tab click event
   
    $('.tab-shipmentsWithOutDeliveryDocumentOnly').on('click', function (e) {
        e.preventDefault();

        // Get the tab content ID based on the data-id attribute

        $.ajax({
            type: "GET",
            url: "{{ route('shipment_completeds.shipment_completed.shipmentsWithOutDeliveryDocumentOnly') }}",
          
            dataType: "json",
            success: function (response) {
                $('#shipmentsWithOutDeliveryDocumentOnly').html("");
                var table = $('.zero-configuration3').DataTable();
                table.destroy();

                $('#shipmentsWithOutDeliveryDocumentOnly').append(response.data);
                
                $('.zero-configuration3').DataTable( {
                    dom: 'lBfrtip',
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: { columns: ':visible' },
                            className: 'btn btn-sm btn-primary' // Custom class for styling
                        }
                    ],
                    language: {
                        buttons: { print: 'طباعة' }
                    },
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    drawCallback: function () {
                        $('.previous').addClass('btn btn-sm btn-dark');
                        $('.paginate_button').addClass('btn btn-sm btn-primary');
                        $('.next').addClass('btn btn-sm btn-dark');
                        $('div.dataTables_filter input').addClass('form-control');
                        $('.dataTables_length').addClass('d-none');
                    }
                });

            }
        });
    });




    $('.tab-shipmentsWithDeliveryDocumentOnly').on('click', function (e) {
        e.preventDefault();

        // Get the tab content ID based on the data-id attribute

        $.ajax({
            type: "GET",
            url: "{{ route('shipment_completeds.shipment_completed.shipmentsWithDeliveryDocumentOnly') }}",
            
            dataType: "json",
            success: function (response) {
                $('#shipmentsWithDeliveryDocumentOnly').html("");
                var table = $('.zero-configuration3').DataTable();
                table.destroy();

                $('#shipmentsWithDeliveryDocumentOnly').append(response.data);
                
                $('.zero-configuration3').DataTable( {
                    dom: 'lBfrtip',
                    buttons: [
                        {
                            extend: 'print',
                            exportOptions: { columns: ':visible' },
                            className: 'btn btn-sm btn-primary' // Custom class for styling
                        }
                    ],
                    language: {
                        buttons: { print: 'طباعة' }
                    },
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    drawCallback: function () {
                        $('.previous').addClass('btn btn-sm btn-dark');
                        $('.paginate_button').addClass('btn btn-sm btn-primary');
                        $('.next').addClass('btn btn-sm btn-dark');
                        $('div.dataTables_filter input').addClass('form-control');
                        $('.dataTables_length').addClass('d-none');
                    }
                });

            }
        });
    });
});

</script>

@endsection