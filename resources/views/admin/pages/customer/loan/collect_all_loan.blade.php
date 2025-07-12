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
        @include('admin.sidebar.sidebar')
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section class="container-fluid mt-5" id="main-content">
            <section class="wrapper">
                <!-- BASIC FORM VALIDATION -->
                <div class="row mt">
                    <div class="col-lg-8 col-md-8">
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
                        <div class="form-panel">
                            <div class="centered">
                                <h3>Loan Collection</h3><br>
                                @if(!empty($customer_data))
                                @foreach ($customer_data as $cus_data)
                                @if(empty(($cus_data->shop_name)))
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @else
                                <h4><b>Name : </b>{{$cus_data->shop_name}}</h4>
                                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                                @endif
                                <h5>Account No : {{$cus_data->acc_no}}</h5>
                                <br>
                                @endforeach
                                @endif
                            </div>
                            <br>
                            @if(!empty($loan_data))
                            @foreach ($loan_data as $loan_data)
                            <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                                action="{{url('/submit_all_loan/'.$loan_data->id)}}" method="post" oncopy="return false"
                                oncut="return false" onpaste="return false">
                                @csrf
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Pending
                                        Loan</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Amount" value="{{$loan_data->pending_loan}}" readonly
                                            name="pending_loan" id="pending_loan" style="cursor: not-allowed;" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Agent
                                        Commission</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Amount" value="{{$loan_data->agent_commission}}" readonly
                                            name="agent_commission" id="agent_commission" style="cursor: not-allowed;" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Interest</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Amount" value="{{$loan_data->interest}}" readonly
                                            name="interest_amount" id="interest_amount" style="cursor: not-allowed;" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Shares
                                        Amount</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Amount" value="{{$loan_data->shares_amount}}" readonly
                                            name="shares_amount" id="shares_amount" style="cursor: not-allowed;" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Total
                                        Amount</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Amount"
                                            value="{{(($loan_data->pending_loan) + ($loan_data->interest) + ($loan_data->agent_commission) - ($loan_data->shares_amount))}}"
                                            readonly name="total_amount" id="total_amount" style="cursor: not-allowed;" class="form-control">
                                        <span>(Pending
                                            Loan + Agent
                                            Commission + Interest - Shares
                                            Amount)</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Pin</label>
                                    <div class="col-lg-4 col-md-4">
                                        <input type="text" placeholder="Pin" name="con_pin" id="con_pin" class="form-control" required>
                                    </div>
                                </div>
                                @if($loan_data->is_interest_calculated==1)
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-lg-offset-2 col-md-offset-2">
                                        <button class="btn btn-theme" type="submit">Submit</button>
                                    </div>
                                </div>
                                @else
                                <div class="form-group">
                                    <div class="col-lg-6 col-md-6 col-lg-offset-2 col-md-offset-2">
                                        <a href="{{url('/calculate_interest/'.$loan_data->id)}}" class="btn btn-theme">
                                            <span>Calculate Interest</span>
                                        </a>
                                    </div>
                                </div>
                                @endif

                            </form>
                            @endforeach
                            @endif
                        </div>
                        <!-- /form-panel -->
                    </div>
                    <!-- /col-lg-12 -->
                </div>
                <!-- /row -->
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
</body>


</html>