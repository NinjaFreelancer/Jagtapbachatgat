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
          </div>
          <!-- /col-lg-12 -->
          <div class="col-lg-12 mt">
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
                            <h4>Balance</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$cus_data->per_month_bachat}}</h3>
                            <h4>Monthly Bachat</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$cus_data->interest}}</h3>
                            <h4>Total Interst</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$cus_data->total_penalty_amount}}</h3>
                            <h4>Total Penalty</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$cus_data->extra_amount}}</h3>
                            <h4>Early Bachat</h4>
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
                    @if(!empty($current_month_bachat_data))
                    <div>
                      <h2>Current Month Status</h2>
                      <div style="overflow-x:auto;">
                        <table class="table">
                          <thead>
                            <tr>
                              <th>credited Amt</th>
                              <th>Pending Amt</th>
                              <th>Start Date</th>
                              <th>End Date</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($current_month_bachat_data as $curr_data)
                            <tr>
                              <td>{{$curr_data->credited}}</td>
                              <td>{{$curr_data->pending}}</td>
                              <td>{{$curr_data->month_start_date}}</td>
                              <td>{{$curr_data->month_end_date}}</td>
                            </tr>
                            @endforeach

                          </tbody>
                        </table>
                      </div>
                    </div>
                    @endif
                    <br>
                    @if(!empty($customer_data))
                    @foreach ($customer_data as $cus_data)
                    @if(($cus_data->is_active)== 1)
                    <!-- <div class="row"> -->
                    <!-- /col-md-6 -->
                    <div class="col-md-12 detailed">
                      <div class="row centered">
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/statement/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Bachat Statement</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/monthly_statement/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Monthly Status</span>
                            </a>
                          </p>
                        </div>
                        @if(!empty($previous_month_bachat_data))
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/pending_bachat/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Pending Bachat</span>
                            </a>
                          </p>
                        </div>
                        @endif
                      </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                    <!-- /col-md-6 -->
                  </div>

                  <!-- /tab-pane -->
                </div>
                <!-- /tab-content -->
              </div>
              <!-- /panel-body -->
            </div>
            <!-- /col-lg-12 -->
          </div>
          <!-- /col-lg-12 -->
          <!-- /col-lg-12 -->
          <div class="col-lg-12 mt">
            <div class="row content-panel">
              <div class="panel-body">
                <div class="tab-content">
                  <div id="overview" class="tab-pane active">
                    <div class="row">
                      <!-- /col-md-6 -->
                      <div class="col-md-12 detailed">
                        <h4>Loan Status</h4>
                        @if(!empty($current_loan_data))
                        @foreach ($current_loan_data as $curr_loan_data)
                        <div class="row centered mt mb">
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$curr_loan_data->amount}}</h3>
                            <h4>Loan Amount</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$curr_loan_data->shares_amount}}
                            </h3>
                            <h4>Shares Amount</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$curr_loan_data->amount - $curr_loan_data->pending_loan}}
                            </h3>
                            <h4>Recovered Loans</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$curr_loan_data->pending_loan}}</h3>
                            <h4>Pending Loan</h4>
                          </div>
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>{{$curr_loan_data->interest}}</h3>
                            <h4>Interest</h4>
                          </div>
                          @if($curr_loan_data->is_interest_calculated == 1)
                          <div class="col-sm-3">
                            <h1><i class="fa fa-money"></i></h1>
                            <h3>
                              {{$curr_loan_data->amount - $curr_loan_data->shares_amount + $curr_loan_data->interest + $curr_loan_data->agent_commission}}
                            </h3>
                            <h4>Total</h4>
                          </div>
                          @endif
                        </div>
                        <div class="row mt mb">
                          <div class="col-sm-2">
                            <h5>Loan Period</h5>
                          </div>
                          <div class="col-sm-5">
                            <h5>Start Date : {{$curr_loan_data->loan_start_date}}</h5>
                          </div>
                          <div class="col-sm-5">
                            <h5>End Date : {{$curr_loan_data->loan_end_date}}</h5>
                          </div>
                        </div>
                        @endforeach
                        @else
                        <div class="row centered mt mb">
                          <div class="col-sm-12">
                            <h3>No Active loan</h3>
                          </div>
                        </div>
                        @endif
                      </div>
                      <!-- /col-md-6 -->
                    </div>
                    <br>
                    <!-- /OVERVIEW -->
                  </div>

                  <div class="row">
                    <!-- /col-md-6 -->
                    <div class="col-md-12 detailed">
                      <div class="row centered">
                        @foreach ($customer_data as $cus_data)
                        @if(($cus_data->is_active)== 1)
                        @if(empty($current_loan_data))
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/give_a_loan/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Give A Loan</span>
                            </a>
                          </p>
                        </div>
                        @else
                        @foreach ($current_loan_data as $loan_data)
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/loan_statement/'.$loan_data->id)}}" class="btn btn-theme">
                              <span>Loan Statement</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/monthly_loan_statement/'.$loan_data->id)}}" class="btn btn-theme">
                              <span>Monthly Status</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/show_loan_details/'.$loan_data->id)}}" class="btn btn-theme">
                              <span>Calculate Interest</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2">
                          <p>
                            <a onclick="return myconfirmation()" href="{{url('/remove_loan/'.$loan_data->id)}}"
                              class="btn btn-theme04">
                              <span>Remove Loan</span>
                            </a>
                          </p>
                        </div>
                        @if(($loan_data->is_interest_calculated) == 1)
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/collect_all_loan/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Collect All Loan</span>
                            </a>
                          </p>
                        </div>
                        @endif
                        @endforeach
                        @endif
                        @if(!empty($previous_loan_data))
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/previous_loans/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Previous Loans</span>
                            </a>
                          </p>
                        </div>
                        @endif
                      </div>
                      @endif
                      @endforeach
                    </div>
                  </div>
                  <!-- /col-md-6 -->
                </div>
                <br>
                <!-- /tab-pane -->
              </div>
              <!-- /tab-content -->
            </div>
            <!-- /panel-body -->
          </div>
          <!-- /col-lg-12 -->
        </div>
        <!-- /col-lg-12 -->
        <div class="col-lg-12 mt">
          <div class="row content-panel">
            <div class="panel-body">
              <div class="tab-content">
                <div id="overview" class="tab-pane active">
                  <div class="row">
                    <!-- /col-md-6 -->
                    <div class="col-md-12 detailed">
                      <div class="row centered">
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/customers')}}" class="btn btn-theme">
                              <span>Back</span>
                            </a>
                          </p>
                        </div>
                        @if(!empty($customer_data))
                        @foreach ($customer_data as $cus_data)
                        @if(($cus_data->is_active)== 1)
                        <div class="col-md-2">
                          <p>
                            <a href="{{url('/edit_profile/'.$cus_data->id)}}" class="btn btn-theme">
                              <span>Edit Profile</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2" id="calculate_interest">
                          <p>
                            <a class="btn btn-theme">
                              <span>Calculate Interes</span>
                            </a>
                          </p>
                        </div>
                        <div class="col-md-2" id="close_account">
                          <p>
                            <a class="btn btn-theme04">
                              <span>Close Account</span>
                            </a>
                          </p>
                        </div>
                        @endif
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
        </div>
        <!-- /row -->
        <div class="col-lg-12 mt" id="calculate_interest_form">
          <div class="row">
            <div class="col-lg-5 col-md-5 content-panel">
              <div class="panel-body">
                <div class="tab-content">
                  <div id="overview" class="tab-pane active">
                    <div class="row">
                      <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                        action="{{url('calculate_monthly_bachat_interest/'.$cus_data->id)}}" method="post"
                        oncopy="return false" oncut="return false" onpaste="return false" enctype="multipart/form-data">
                        @csrf
                        <div class="centered">
                          <h4>Calculate Interest (Monthly)</h4>
                        </div>
                        <br>
                        <div class="row">
                          <div class="form-group">
                            <label class="col-lg-4 col-md-4 col-lg-offset-1 col-md-offset-1 control-label">calculate up
                              to</label>
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
                          <div class="form-group">
                            <label class="col-lg-4 col-md-4 col-lg-offset-1 col-md-offset-1 control-label">Pin</label>
                            <div class="col-lg-5 col-md-5">
                              <input type="password" placeholder="Pin" name="con_pin" id="con_pin" class="form-control"
                                required>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                            <button class="btn btn-theme" type="submit">Calculate
                              Interest</button>
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
            </div>

            <div class="col-lg-5 col-md-5 col-lg-offset-1 col-md-offset-1 content-panel">
              <div class="panel-body">
                <div class="tab-content">
                  <div id="overview" class="tab-pane active">
                    <div class="row">
                      <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                        action="{{url('calculate_bachat_interest/'.$cus_data->id)}}" method="post" oncopy="return false"
                        oncut="return false" onpaste="return false" enctype="multipart/form-data">
                        @csrf
                        <div class="centered">
                          <h4>Calculate Interest (5 Year's)</h4>
                        </div>
                        <br>
                        <div class="row">
                          <div class="form-group">
                            <label class="col-lg-4 col-md-4 col-lg-offset-1 col-md-offset-1 control-label">Pin</label>
                            <div class="col-lg-5 col-md-5">
                              <input type="password" placeholder="Pin" name="con_pin" id="con_pin" class="form-control"
                                required>
                            </div>
                          </div>
                          <div class="col-lg-12 col-md-12 col-xs-12 col-lg-offset-1 col-md-offset-1">
                            <button class="btn btn-theme" type="submit">Calculate
                              Interest</button>
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
            </div>
            <!-- /panel-body -->
          </div>
          <!-- /col-lg-12 -->
        </div>
        <div class="col-lg-12 mt" id="confirmation">
          <div class="row content-panel">
            <div class="panel-body">
              <div class="tab-content">
                <div id="overview" class="tab-pane active">
                  <div class="row">
                    <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                      action="{{url('close_account/'.$cus_data->id)}}" method="post" oncopy="return false"
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
                          <button class="btn btn-theme04" type="submit">Close
                            Account</button>
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
    <script src="lib/form-validation-script.js"></script>

    <script type="text/javascript" src="{{asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('lib/advanced-form-components.js')}}"></script>
    <script>
      $(document).ready(function() {
        $("#confirmation").hide();
        $("#calculate_interest_form").hide();

        $("#close_account").click(function() {
          $("#close_account").hide();
          $("#calculate_interest_form").hide();
          $("#confirmation").show();
          $("#calculate_interest").show();
        });

        $("#calculate_interest").click(function() {
          $("#calculate_interest").hide();
          $("#confirmation").hide();
          $("#calculate_interest_form").show();
          $("#close_account").show();
        });
      });

      // function myconfirmation() {
      //     var r = confirm("Are You Sure.");
      //     if (r == true) {
      //         return true;
      //     } else {
      //         return false;
      //     }
      // }

      function myconfirmation() {
        var r = confirm("Are You Sure.");
        if (r == true) {
          return true;
        } else {
          return false;
        }
      }
    </script>
    <!--footer end-->
  </section>
  <!-- js placed at the end of the document so the pages load faster -->
</body>







</html>