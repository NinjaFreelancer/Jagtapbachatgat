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
              <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                action="{{url('/submit_missed_bachat_collection')}}" method="post" oncopy="return false"
                oncut="return false" onpaste="return false">
                @csrf
                <div class="centered">
                  <h3>Missed bachat collection</h3><br>
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
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"><b>Customer
                      Id</b></label>
                  <div class="col-lg-4 col-md-4">
                    <input type="number" placeholder="Enter The Customer Id" id="customer_id" name="customer_id"
                      class="form-control prevent-autofill" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Amount</label>
                  <div class="col-lg-4 col-md-4">
                    <input type="number" placeholder="Amount" name="amount" id="amount"
                      class="form-control prevent-autofill" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Details</label>
                  <div class="col-lg-4 col-md-4">
                    <input type="text" placeholder="Details" name="details" id="details"
                      class="form-control prevent-autofill" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Collection Date</label>
                  <div class="col-lg-4 col-xs-8">
                    <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="01-01-2020"
                      class="input-append date dpYears">
                      <input type="text" readonly="" size="16" class="form-control" name="collection_date"
                        id="collection_date">
                      <span class="input-group-btn add-on">
                        <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                    <span class="help-block">Select date</span>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label">Pin</label>
                  <div class="col-lg-4 col-md-4">
                    <input type="password" placeholder="Pin" name="con_pin" id="con_pin"
                      class="form-control prevent-autofill" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-lg-6 col-md-6 col-lg-offset-2 col-md-offset-2">
                    <button class="btn btn-theme" type="submit">Submit</button>
                  </div>
                </div>
              </form>
            </div>
            <!-- /form-panel -->
          </div>
          <!-- /col-lg-12 -->
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
    document.addEventListener('DOMContentLoaded', function() {
      const fields = document.querySelectorAll('.prevent-autofill');
      fields.forEach(function(field) {
        field.addEventListener('focus', function() {
          field.removeAttribute('readonly');
        });
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
</body>


</html>