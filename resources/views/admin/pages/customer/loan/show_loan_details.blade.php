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
                    <div class="col-sm-12">
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
                    </div>
                    <div class="col-lg-6 col-md-8 col-sm-12">

                        <div class="form-panel">
                            <div class="centered">
                                <h3>Loan Details</h3><br>

                                <br>

                            </div>
                            <br>

                            <!-- /form-panel -->
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-11 col-xs-10">
                                    @if(!empty($loan_data))
                                    @foreach ($loan_data as $l_data)
                                    <h4><b>Loan no : </b>{{$l_data->loan_no}}</h4>
                                    <h4><b>Amount : </b>{{$l_data->amount}}</h4>
                                    <h4><b>Monthly Emi : </b>{{$l_data->monthly_emi}}</h4>
                                    <h4><b>Shares Amount : </b>{{$l_data->shares_amount}}</h4>
                                    <h4><b>Pending Loan : </b>{{$l_data->pending_loan}}</h4>
                                    @if($l_data->is_interest_calculated == 1)
                                    <h4><b>Interest : </b>{{$l_data->interest}}</h4>
                                    <h4><b>Agent Commission : </b>{{$l_data->agent_commission}}</h4>
                                    <h4><b>Total Amount :
                                        </b>{{ $l_data->pending_loan + $l_data->interest + $l_data->agent_commission - $l_data->shares_amount }}
                                    </h4>
                                    <h4><b>Completed Months : </b>{{$l_data->completed_months}}</h4>
                                    <h4><b>Extra Days : </b>{{$l_data->extra_days}}</h4>
                                    <h4><b>Interest Calculated Up To : </b>{{$l_data->interest_calculated_up_to}}</h4>

                                    @if(($l_data->is_interest_calculated == 1) && ($l_data->status ==
                                    0))
                                    <p id="recalculate_interest">
                                        <a class="btn btn-theme">
                                            <span>Recalculate Interest</span>
                                        </a>
                                    </p>
                                    <p>
                                        <a href="{{url('/collect_all_loan/'.$l_data->customer_id)}}" class="btn btn-theme">
                                            <span>Collect All Loan</span>
                                        </a>
                                    </p>
                                    @endif
                                    @else
                                    <p id="calculate_interest">
                                        <a class="btn btn-theme">
                                            <span>Calculate Interest</span>
                                        </a>
                                    </p>
                                    @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <br>
                            <br>
                            @if(!empty($loan_data))
                            @foreach ($loan_data as $l_data)
                            <div class="row" id="calculate_interest_form">
                                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-9">
                                    <form role="form" class="form-horizontal style-form centered" onsubmit="return myconfirmation()"
                                        action="{{url('calculate_interest/'.$l_data->id)}}" method="post" oncopy="return false"
                                        oncut="return false" onpaste="return false">
                                        @csrf
                                        <div class="form-group">
                                            <label class="control-label col-lg-4">Calculate Up To</label>
                                            <div class="col-lg-4 col-xs-8">
                                                <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="01-01-2020"
                                                    class="input-append date dpYears">
                                                    <input type="text" readonly="" size="16" class="form-control" name="calculate_up_to"
                                                        id="calculate_up_to">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                                <span class="help-block">Select date</span>
                                            </div>
                                        </div>
                                        <div class="form-group" id="InterestRate">
                                            <label class="col-lg-4 col-md-4 col-sm-4 control-label"><b>Interest
                                                    Rate</b></label>
                                            <div class="col-lg-8 col-md-8 col-sm-8">
                                                <select name="interest_rate" class="form-control" id="select_action">
                                                    <option selected disabled>Select Interest Rate</option>
                                                    <option value="1">1%</option>
                                                    <option value="2">2%</option>
                                                    <option value="3">3%</option>
                                                    <option value="4">4%</option>
                                                    <option value="5">5%</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-6">
                                                <button class="btn btn-theme" type="submit">Calculate</button>
                                            </div>
                                        </div>
                                    </form>
                                    <br>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <br>
                    </div>
                </div>
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

    <script type="text/javascript" src="{{asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('lib/advanced-form-components.js')}}"></script>
    <script>
        function myconfirmation() {
            var r = confirm("Are You Sure.");
            if (r == true) {
                return true;
            } else {
                return false;
            }
        }

        $(document).ready(function() {
            $("#calculate_interest_form").hide();
            $("#calculate_interest").click(function() {
                $("#calculate_interest").hide();
                $("#calculate_interest_form").show();
            });
            $("#recalculate_interest").click(function() {
                $("#recalculate_interest").hide();
                $("#calculate_interest_form").show();
            });
        });
    </script>
</body>

</html>