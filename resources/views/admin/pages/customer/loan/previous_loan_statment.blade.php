<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    @include('head.head')

</head>

<body>
    <section id="container">
        <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
        <!--header start-->
        @include('admin.sidebar.sidebar')
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section class="container-fluid" id="main-content">
            <section class="wrapper">
                <h3>Previous Loan Statement</h3>
                <div class="row">
                    <div class="col-md-12">
                        @if(Session::has('message'))
                        <div class="showback">
                            <h3>Alert!</h3>
                            <p class="alert alert-success">
                                {{ Session::get('message') }}
                                {{ Session::forget('message') }}
                            </p>
                        </div>
                        @endif
                        @if(Session::has('error'))
                        <div class="showback">
                            <h3>Alert!</h3>
                            <p class="alert alert-danger">
                                {{ Session::get('error') }}
                                {{ Session::forget('error') }}
                            </p>
                        </div>
                        @endif
                        <div class="content-panel">
                            <Div class="centered">
                                <h3>Jagtap Bachatgat</h3>
                                @if(!empty($customer_data))
                                @foreach ($customer_data as $cus_data)
                                @if(!empty($cus_data->full_name))
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @else
                                <h4><b>Name : </b>{{$cus_data->shop_name}}</h4>
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @endif
                                <h5>Account No : {{$cus_data->acc_no}}</h5>
                                <br>
                                <h4>DETAILS OF LOAN STATEMENT</h4>
                                @endforeach
                                @endif
                            </Div>
                            <br>
                            <br>

                            <!-- <hr> -->
                            <section class="container-fluid">
                                <div class="row mb">
                                    <!-- page start-->
                                    <div class="content-panel">
                                        <div class="adv-table" style="overflow-x:auto;">
                                            <table cellpadding="0" cellspacing="0" border="0"
                                                class="display table table-bordered" id="hidden-table-info">
                                                <thead>
                                                    <tr>
                                                        <th>Sr.No</th>
                                                        <th>Transaction Date & Time</th>
                                                        <th>Amount(Rs.)</th>
                                                        <th>Pending Loan(Rs.)</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($loan_statement_data))
                                                    @foreach ($loan_statement_data as $data)
                                                    <tr>
                                                        <td>{{++$sr}}</td>
                                                        <td>{{$data->trans_date}} {{$data->trans_time}}</td>
                                                        <td>{{$data->amount}}</td>
                                                        @if($data->details == "Agent Commission")
                                                        <td>{{0}}</td>
                                                        @else
                                                        <td>{{($pending=($pending - $data->amount))}}</td>
                                                        @endif
                                                        <td>{{($data->details)}}</td>
                                                    </tr>
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <!-- page end-->
                                </div>
                                <!-- /row -->
                            </section>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <!-- /MAIN CONTENT -->
        <!--main content end-->
        <!--footer start-->
        @include('footer.footer')
        <!--footer end-->
        <script src="{{asset('lib/common-scripts.js')}}"></script>
        <script type="text/javascript" language="javascript"
            src="{{asset('lib/advanced-datatable/js/jquery.dataTables.js')}}"></script>
        <script type="text/javascript" src="{{asset('lib/advanced-datatable/js/DT_bootstrap.js')}}"></script>

        <!--script for this page-->
        <script type="text/javascript">
            $(document).ready(function() {
                var oTable = $('#hidden-table-info').dataTable({
                    "aoColumnDefs": [{
                        // "bSortable": false,
                        "aTargets": [0]
                    }],
                    "aaSorting": [
                        [0, 'asc']
                    ]
                });

            });

            function myconfirmation() {
                var r = confirm("Are You Sure.");
                if (r == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
    </section>
</body>

</html>