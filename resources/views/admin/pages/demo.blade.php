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
        <section id="main-content">
            <section class="wrapper">
                <h3> Give A Loan</h3>
                <div class="row mt">
                    <!--  DATE PICKERS -->
                    <div class="col-lg-8">
                        <div class="form-panel">
                            @if($data != 0)
                            @foreach ($customer_data as $cus_data)
                            <Div class="centered">
                                <h3>Jagtap Bachatgat</h3>
                                @if(1 == 0)
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @else
                                <h4><b>Name : </b>{{$cus_data->shop_name}}</h4>
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @endif
                                <h5>Cust Id : {{$cus_data->id}}</h5>
                                <h5>Account No : {{$cus_data->acc_no}}</h5>
                                <br>
                            </Div>
                            <br>
                            <br>
                            <form role="form" class="form-horizontal style-form"
                                action="{{url('submit_loan/'.$cus_data->id)}}" method="post" oncopy="return false"
                                oncut="return false" onpaste="return false" enctype="multipart/form-data">
                                @csrf
                                @endforeach
                                @endif
                                <div class="form-group">
                                    <label
                                        class="control-label col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">Loan
                                        Start Date</label>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy"
                                            data-date="01-01-2020" class="input-append date dpYears">
                                            <input type="text" readonly="" size="16" class="form-control"
                                                name="start_date" id="start_date">
                                            <span class="input-group-btn add-on">
                                                <button class="btn btn-theme" type="button"><i
                                                        class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                        <span class="help-block">Select date</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="control-label col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">Loan
                                        End Date</label>
                                    <div class="col-lg-3 col-md-3 col-sm-3">
                                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy"
                                            data-date="01-01-2020" class="input-append date dpYears">
                                            <input type="text" readonly="" size="16" class="form-control"
                                                name="end_date" id="end_date">
                                            <span class="input-group-btn add-on">
                                                <button class="btn btn-theme" type="button"><i
                                                        class="fa fa-calendar"></i></button>
                                            </span>
                                        </div>
                                        <span class="help-block">Select date</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 control-label">Loan
                                        Amount</label>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="text" placeholder="Enter The Loan Amount" name="loan_amount"
                                            id="loan_amount" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label
                                        class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 control-label">Pin</label>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="text" placeholder="Enter The Pin" name="con_pin" id="con_pin"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">
                                        <button class="btn btn-theme" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <!-- /form-panel -->
                    </div>
                    <!-- /col-lg-12 -->
                </div>
                <!-- /row -->
            </section>
            <!-- /wrapper -->
        </section>
        <!-- /MAIN CONTENT -->
        <!--main content end-->
        <!--footer start-->
        @include('footer.footer')
        <!--footer end-->
    </section>
    <script src="{{asset('lib/common-scripts.js')}}"></script>
    <script src="lib/form-validation-script.js"></script>


    <script type="text/javascript" src="lib/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
    <script src="lib/advanced-form-components.js"></script>

</body>

</html>