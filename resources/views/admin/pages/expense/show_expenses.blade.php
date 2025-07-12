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

        @include('admin.sidebar.sidebar')

        <section class="container-fluid" id="main-content">
            <section class="wrapper">
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
                <h3>Expenses</h3>
                <div class="row">
                    <div class="col-md-12">
                        <div class="content-panel">
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
                                                        <th>Amount</th>
                                                        <th>Details</th>
                                                        <th>Date</th>
                                                        <th>Transaction Date & Time</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(!empty($expenses_data))
                                                    @foreach ($expenses_data as $data)
                                                    <tr>
                                                        <td>{{++$sr}}</td>
                                                        <td>{{$data->amount}}</td>
                                                        <td>{{$data->details}}</td>
                                                        <td>{{$data->expenses_date}}</td>
                                                        <td>{{$data->trans_date}} {{$data->trans_time}}</td>
                                                        <td><a onclick="return myconfirmation()"
                                                                href="{{url('/remove_expense/'.$data->id)}}"
                                                                class="btn btn-danger btn-xs fa fa-trash-o">
                                                            </a>
                                                        </td>
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
        </script>
        <script>
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