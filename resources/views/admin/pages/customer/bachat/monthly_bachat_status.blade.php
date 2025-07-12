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
                <h3>Jagtap Bachatgat</h3>
                @if(!empty($customer_data))
                @foreach ($customer_data as $cus_data)
                @if(!empty(($cus_data->full_name)))
                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                @else
                <h4><b>Name : </b>{{$cus_data->shop_name}}</h4>
                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                @endif
                <h5>Account No : {{$cus_data->acc_no}}</h5>
                <br>
                <h4>DETAILS OF MONTHLY BACHAT STATUS</h4>
                @endforeach
                @endif
              </div>
              <br>
              <!-- /form-panel -->
              <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                </div>
                <div class="col-lg-10 col-md-10 col-sm-11 col-xs-10">
                  @if(!empty($monthly_bachat_status_data))
                  @foreach ($monthly_bachat_status_data as $data)
                  <h4><b>Month Start Date : </b>{{$data->month_start_date}}</h4>
                  <h4><b>Month END Date : </b>{{$data->month_end_date}}</h4>
                  <h4><b>Monthly Bachat Amount : </b>{{$data->monthly_bachat_amount}}</h4>
                  <h4><b>Credited Amount : </b>{{$data->credited}}</h4>
                  <h4><b>Pending Amount : </b>{{$data->pending}}</h4>
                  <h4><b>Pending Amount Collected On : </b>{{$data->pending_amount_collected_on}}</h4>
                  <h4><b>Interest : </b>{{$data-> interest}}</h4>
                  @if($data->is_penalty_applicable == 1)
                  <h4><b>Is Penalty Applicable : </b>Yes</h4>
                  <h4><b>Penalty Amount : </b>{{$data->penalty_amount}}</h4>
                  <h4><b>Bachat Pending Months : </b>{{$data->bachat_pending_months}}</h4>
                  <h4><b>Penalty Calculate Up To : </b>{{$data->penalty_calculate_up_to}}</h4>
                  <!-- @if($data->has_the_penalty_been_calculated == 0)
                  <p id="calculate_interest">
                    <a class="btn btn-theme">
                      <span>Calculate Penalty</span>
                    </a>
                  </p>
                  @elseif($data->has_the_penalty_been_collected == 0)

                  <p>
                    <a class="btn btn-theme" id="recalculate_interest">
                      <span>Recalculate Penalty</span>
                    </a>
                  </p>
                  <p>
                    <a href="{{url('/collect_penalty/'.$data->id)}}" class="btn btn-theme">
                      <span>Collect Penalty</span>
                    </a>
                  </p>
                  @endif -->
                  @endif
                  @endforeach
                  @endif
                </div>
              </div>

              @if(!empty($monthly_bachat_status_data))
              @foreach ($monthly_bachat_status_data as $data)
              <div class="row" id="calculate_interest_form">
                <div class="col-lg-2 col-md-2 col-sm-1 col-xs-1">
                </div>
                <div class="col-lg-8 col-md-8 col-sm-10 col-xs-9">
                  <form role="form" class="form-horizontal style-form centered" onsubmit="return myconfirmation()"
                    action="{{url('calculate_penalty/'.$data->id)}}" method="post" oncopy="return false"
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
        <div class="col-lg-12 mt">
          <div class="row content-panel">
            <div class="panel-body">
              <div class="tab-content">
                <div id="overview" class="tab-pane active">
                  <div class="row centered">
                    @if(!empty($customer_data))
                    @foreach ($customer_data as $cus_data)
                    <div class="col-md-2">
                      <p><a href="{{url('/monthly_statement/'.$cus_data->id)}}"
                          class="btn btn-theme"><span>Back</span></a></p>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
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