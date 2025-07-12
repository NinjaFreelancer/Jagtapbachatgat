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
            @if(!empty($fixed_deposit_data))
            @foreach ($fixed_deposit_data as $fd_data)
            <div class="form-panel">
              <div class="centered">
                <h3>Fixed Deposit</h3><br>
                <h4><b>Cust Name : </b>{{$fd_data->customer_name}}</h4>
                <h4><b>Mobile No : </b>{{$fd_data->mobile_no}}</h4>
                <br>
              </div>
              <br>
              <!-- /form-panel -->
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                </div>
                <div class="col-lg-10 col-md-10 col-sm-11 col-xs-10">
                  <h4><b>Amount : </b>{{$fd_data->FD_amount}}</h4>
                  <h4><b>Date Of Deposit : </b>{{$fd_data->date_of_deposit}}</h4>
                  @if($fd_data->is_interest_calculated == 1)
                  <h4><b>Interest : </b>{{$fd_data->interest}}</h4>
                  <h4><b>Total Disbursement Amount : </b>{{$fd_data->FD_amount + $fd_data->interest}}</h4>
                  @if($fd_data->is_interest_calculate_by_yearly == 1)
                  <h4><b>Completed Years : </b>{{$fd_data->completed_years}}</h4>
                  @else
                  <h4><b>Completed Months : </b>{{$fd_data->completed_months}}</h4>
                  <h4><b>Extra Days : </b>{{$fd_data->extra_days}}</h4>
                  @endif
                  <h4><b>Interest Rate : </b>{{$fd_data->interest_rate}} %</h4>
                  <h4><b>Interest Calculated Up To : </b>{{$fd_data->interest_calculated_up_to}}</h4>

                  @if(($fd_data->is_interest_calculated == 1) && ($fd_data->is_fd_amount_disbursed ==
                  0))
                  <p id="recalculate_interest">
                    <a class="btn btn-theme">
                      <span>Recalculate Interest</span>
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
                </div>
              </div>
              <br>
              <br>
              <div class="row" id="calculate_interest_form">
                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-9">
                  <form role="form" class="form-horizontal style-form centered" onsubmit="return myconfirmation()"
                    action="{{url('calculate_fd_interest/'.$fd_data->id)}}" method="post" oncopy="return false"
                    oncut="return false" onpaste="return false">
                    @csrf
                    <div class="form-group">
                      <label class="col-lg-5 col-md-5 control-label">
                        <b>Interest Calculate By</b></label>
                      <div class="radio col-lg-7 col-md-7 text-left">
                        <input type="radio" name="interest_for" id="InterestCalculateByYearly" value="1">
                        Yearly
                        <br> <br>
                        <input type="radio" name="interest_for" id="InterestCalculateByMonthly" value="2">
                        Monthly
                        <br> <br>
                      </div>
                    </div>
                    <div class="form-group" id="InterestRate">
                      <label class="col-lg-4 col-md-4 col-sm-4 control-label"><b>Interest
                          Rate</b></label>
                      <div class="col-lg-8 col-md-8 col-sm-8">
                        <select name="interest_rate" class="form-control" id="select_action">
                          <option selected disabled>Select Action</option>
                          <option value="9">9%</option>
                          <option value="10">10%</option>
                          <option value="11">11%</option>
                          <option value="12">12%</option>
                          <option value="13">13%</option>
                          <option value="14">14%</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group" id="CalculateUpTo">
                      <label class="control-label col-lg-4 col-md-4 col-sm-4"><b>Calculate Up To</b></label>
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
                      <div class="col-lg-6">
                        <button class="btn btn-theme" type="submit">Calculate</button>
                      </div>
                    </div>
                  </form>
                  <br>
                </div>
              </div>
              @if(($fd_data->is_interest_calculated == 1) && ($fd_data->is_fd_amount_disbursed == 0))
              <div class="row" id="close_fd_confirmation">
                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-9">
                  <form role="form" class="form-horizontal style-form centered" onsubmit="return myconfirmation()"
                    action="{{url('disburse_fd_amount/'.$fd_data->id)}}" method="post" oncopy="return false"
                    oncut="return false" onpaste="return false">
                    @csrf
                    <div class="centered">
                      <h4>
                        Disburse Fixed Deposit Amount.
                      </h4>
                    </div>
                    <br>
                    <div class="form-group">
                      <label class="control-label col-lg-4">Disbursal Date</label>
                      <div class="col-lg-4 col-xs-8">
                        <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="01-01-2020"
                          class="input-append date dpYears">
                          <input type="text" readonly="" size="16" class="form-control" name="disbursal_date"
                            id="disbursal_Date">
                          <span class="input-group-btn add-on">
                            <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                          </span>
                        </div>
                        <span class="help-block">Select date</span>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-4 col-md-4 col-sm-4 control-label">Pin</label>
                      <div class="col-lg-8 col-md-8 col-sm-8">
                        <input type="text" placeholder="Pin" name="con_pin" id="con_pin" class="form-control" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-6">
                        <button class="btn btn-theme04" type="submit">Submit</button>
                      </div>
                    </div>
                  </form>
                  <br>
                </div>
              </div>
              @endif
            </div>
            <br>
            @endforeach
            @endif
          </div>

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
        $("#close_fd_confirmation").hide();
        $("#calculate_interest_form").show();
      });
    });

    $("#InterestCalculateByYearly").click(function() {
      $("#CalculateUpTo").hide();
    });

    $("#InterestCalculateByMonthly").click(function() {
      $("#CalculateUpTo").show();
    });
  </script>
</body>








</html>