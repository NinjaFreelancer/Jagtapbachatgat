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
            <section class="wrapper site-min-height">
                <div class="row mt">
                    <div class="col-lg-12">
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
                        <div class="row content-panel">
                            <!-- /col-md-4 -->
                            @if(!empty($customer_data))
                            @foreach ($customer_data as $cus_data)
                            <div class="col-md-4 centered">
                                @if(empty(($cus_data->shop_name)))
                                <h4><b>Account Type : </b>Persnol</h4>
                                @else
                                <h4><b>Account Type : </b>Bussiness</h4>
                                @endif

                                <div class="profile-pic">
                                    <a href="{{asset('profile/'.$cus_data->profile)}}"><img src="{{asset('profile/'.$cus_data->profile)}}"
                                            class="img-circle"></a>
                                    <!-- <p>
                                        <button class="btn btn-theme02"><i class="fa fa-pencil"></i> Edit
                                            Profile</button>
                                    </p> -->
                                </div>
                            </div>
                            <!-- /col-md-4 -->
                            <div class="col-md-8 profile-text">
                                @if(empty(($cus_data->shop_name)))
                                <h3><b>Cust Name : </b>{{$cus_data->full_name}}</h3>
                                @else
                                <h3><b>Name : </b>{{$cus_data->shop_name}}</h3>
                                <h4><b>Customer Name : </b>{{$cus_data->full_name}}</h4>
                                @endif
                                <h5>Cust Id : {{$cus_data->id}}</h5>
                                <h5>Account No : {{$cus_data->acc_no}}</h5>
                                <h5>PAN No : {{$cus_data->pan}}</h5>
                                <h5>Addhar No : {{$cus_data->aadhaar}}</h5>
                                <h5>Account Open Date : {{$cus_data->account_opening_date}}</h5>
                                <h5>Account Expire Date : {{$cus_data->account_expiry_date}}</h5>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <!-- /row -->

                        <div class="row content-panel">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="overview" class="tab-pane active">
                                        <div class="row">
                                            <!-- /col-md-6 -->
                                            <div class="col-md-12 detailed">
                                                <h4>Bachat Status</h4>
                                                @if(!empty($customer_data))
                                                @foreach ($customer_data as $cus_data)
                                                <div class="row centered mt mb">
                                                    <div class="col-sm-3">
                                                        <h1><i class="fa fa-money"></i></h1>
                                                        <h3>{{$cus_data->balance}}</h3>
                                                        <h4>Bachat</h4>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h1><i class="fa fa-money"></i></h1>
                                                        <h3>{{$cus_data->interest}}</h3>
                                                        <h4>Interest</h4>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h1><i class="fa fa-money"></i></h1>
                                                        <h3>{{$cus_data->balance + $cus_data->interest}}</h3>
                                                        <h4>Disbursal Amount</h4>
                                                    </div>
                                                    <div class="col-sm-3">
                                                        <h1><i class="fa fa-money"></i></h1>
                                                        <h3>{{$cus_data->loan_revenue_from_loan}}</h3>
                                                        <h4>Total Loan Revenue</h4>
                                                    </div>
                                                </div>
                                                @endforeach
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="detailed">

                                                </div>
                                            </div>
                                            <!-- /col-md-6 -->
                                        </div>
                                    </div>
                                    <!-- /tab-pane -->
                                </div>
                                <!-- /tab-content -->
                            </div>
                            <!-- /panel-body -->
                        </div>
                        <!-- /col-lg-12 -->

                        <div class="row content-panel">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="overview" class="tab-pane active">
                                        <div class="row">
                                            <!-- /col-md-6 -->
                                            <div class="col-md-12 detailed">
                                                <div class="row centered">
                                                    @if(!empty($customer_data))
                                                    @foreach ($customer_data as $cus_data)

                                                    <div class="col-md-2">
                                                        @if(($cus_data->status)== 0)
                                                        <p>
                                                            <a href="{{url('/block_customer/'.$cus_data->id)}}" class="btn btn-theme04">
                                                                <span>Block</span>
                                                            </a>
                                                        </p>
                                                        @elseif(($cus_data->status)== 1)
                                                        <p>
                                                            <a href="{{url('/unblock_customer/'.$cus_data->id)}}" class="btn btn-theme">
                                                                <span>Unblock</span>
                                                            </a>
                                                        </p>
                                                        @endif
                                                    </div>
                                                    <div class="col-md-2">
                                                        @if(($cus_data->is_account_ready_to_reuse)== 0)
                                                        <p id="clear_data">
                                                            <a class="btn btn-theme04">
                                                                <span>Clear Data</span>
                                                            </a>
                                                        </p>
                                                        @else
                                                        <p>
                                                            <a href="{{url('/reuse_account/'.$cus_data->acc_no)}}" class="btn btn-theme">
                                                                <span>Reuse Account</span>
                                                            </a>
                                                        </p>
                                                        @endif
                                                    </div>
                                                    @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- /col-md-6 -->
                                        </div>
                                        <!-- /OVERVIEW -->
                                    </div>
                                    <!-- /tab-pane -->
                                </div>
                                <!-- /tab-content -->
                            </div>
                            <!-- /panel-body -->
                        </div>
                        <!-- /col-lg-12 -->

                        <div class="row content-panel" id="confirmation">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div id="overview" class="tab-pane active">
                                        <div class="row">
                                            <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                                                action="{{url('clear_data/'.$cus_data->id)}}" method="post" oncopy="return false"
                                                oncut="return false" onpaste="return false" enctype="multipart/form-data">
                                                @csrf
                                                <div class="centered">
                                                    <h3>Confirmation</h3>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <div class="col-lg-4 col-md-3">

                                                    </div>
                                                    <div class="col-lg-4 col-md-6" style="text-align: center;">
                                                        <div class="form-group">
                                                            <label class="col-lg-4 col-md-4 control-label">Enter
                                                                Pin</label>
                                                            <div class="col-lg-8 col-md-8">
                                                                <input type="password" name="pin" id="pin" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-theme04" type="submit">Clear
                                                            Data</button>
                                                    </div>
                                                </div>
                                            </form>
                                            <!-- /col-md-6 -->
                                        </div>
                                        <!-- /OVERVIEW -->
                                    </div>
                                    <!-- /tab-pane -->
                                </div>
                                <!-- /tab-content -->
                            </div>
                            <!-- /panel-body -->
                        </div>
                        <!-- /col-lg-12 -->
                    </div>
                </div>
                <!-- /container -->
            </section>
            <!-- /wrapper -->
        </section>
        <!-- /MAIN CONTENT -->
        <!--main content end-->

        <!--footer start-->
        @include('footer.footer')
        <script src="{{asset('lib/common-scripts.js')}}"></script>
        <script>
            $(document).ready(function() {
                $("#confirmation").hide();
                $("#clear_data").click(function() {
                    $("#clear_data").hide();
                    $("#confirmation").show();
                });
            });
        </script>
    </section>
</body>


</html>