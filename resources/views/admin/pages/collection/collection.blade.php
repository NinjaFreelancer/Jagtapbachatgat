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
              <form role="form" autocomplete="off" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                action="{{url('submit_collection')}}" method="post" oncopy="return false" oncut="return false"
                onpaste="return false">
                @csrf
                <div class="centered">
                  <h3>Collection</h3>
                </div>
                <br>

                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"><b>Customer
                      Id</b></label>
                  <div class="col-lg-4 col-md-4">
                    <input type="number" placeholder="Enter The Customer Id" id="customer_id" name="customer_id"
                      class="form-control prevent-autofill" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2 control-label">
                    <h4><b>Customer Info</b></h4>
                    <div id="cust_info">
                    </div>
                  </label>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"> <b>Select</b></label>
                  <div class="radio col-lg-4 col-md-4">
                    <label>
                      <input type="radio" name="collection" id="optionsRadios1" value="1">
                      Bachat Collection
                    </label><br> <br>
                    <label>
                      <input type="radio" name="collection" id="optionsRadios2" value="2">
                      Loan Collection
                    </label> <br> <br>
                  </div>
                </div>
                <div id="collection Details">

                </div>
                <div class="form-group">
                  <label class="col-lg-8 col-md-8 col-lg-offset-2 col-md-offset-2 control-label">
                    <h4><b>Collection Details</b></h4>
                    <h5><b>Collection Type :- </b><span id="collection_type"></span></h5>
                    <h5><b>Current Month Start Date :- </b><span id="month_start_date"></span></h5>
                    <h5><b>Current Month End Date :- </b><span id="month_end_date"></span></h5>
                    <h5><b>Total Pending Amount :- </b><span id="pending_amount"></span></h5>
                  </label>
                </div>
                <!-- <div class="form-group">
                  <label class="col-lg-2 col-md-8 col-lg-offset-2 col-md-offset-2 control-label">Bachat Month</label>
                  <div class="col-lg-4 col-md-3 col-xs-8">
                    <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="01-01-2020"
                      class="input-append date dpYears">
                      <input type="text" readonly="" size="16" class="form-control" name="bachat_month"
                        id="bachat_month">
                      <span class="input-group-btn add-on">
                        <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                      </span>
                    </div>
                    <span class="help-block">Select date</span>
                  </div>
                </div> -->

                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"><b>Amount</b></label>
                  <div class="col-lg-4 col-md-4">
                    <input type="text" placeholder="Amount" name="amount" id="amount"
                      class="form-control prevent-autofill" autocomplete="off" required readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"><b>Details</b></label>
                  <div class="col-lg-4 col-md-4">
                    <input type="text" placeholder="Details" name="details" id="details"
                      class="form-control prevent-autofill" readonly>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-lg-2 col-md-2 col-lg-offset-2 col-md-offset-2 control-label"><b>Pin</b></label>
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
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const fields = document.querySelectorAll('.prevent-autofill');
      fields.forEach(function(field) {
        field.addEventListener('focus', function() {
          field.removeAttribute('readonly');
        });
      });
    });

    $("#customer_id").change(function() {
      var id = $(this).val();
      document.getElementById('optionsRadios1').checked = false;
      document.getElementById('optionsRadios2').checked = false;
      var url = window.location.origin + '/get_customer_data/' + id;
      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response) {

          var len = 0;
          $('#cust_info').empty();

          if (response['cuss_data'] != null) {
            len = response['cuss_data'].length;

          }

          var acc_no = response['cuss_data'][0].acc_no;
          var full_name = response['cuss_data'][0].full_name;
          var shop_name = response['cuss_data'][0].shop_name;
          if (shop_name != null) {
            $("#cust_info").append("<b>Account Number:</b> " + acc_no +
              "<br><b>Shop Name:</b> " +
              shop_name + "<br><b>Customer Name:</b> " +
              full_name);
          } else {
            $("#cust_info").append("<b>Account Number:</b> " + acc_no +
              "<br><b>Customer Name:</b> " +
              full_name);
          }
          console.log(tr_str);
        },
        error(data) {
          // console.log(data);
        }
      });
    });

    $("#optionsRadios1").click(function() {
      var customer_id = document.getElementById("customer_id").value;
      if (customer_id == '') {
        alert('First Enter Customer Id.');
        document.getElementById("customer_id").style.borderColor = "red";
        document.getElementById("customer_id").focus();
        return false;

      }

      var url = window.location.origin + '/get_pending_bachat/' + customer_id;
      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        success: function(response) {

          $('#collection_type').empty();
          $('#month_start_date').empty();
          $('#month_end_date').empty();
          $('#pending_amount').empty();


          // var pending_amount = response['pending_bachat_data'][0].pending;
          var month_start_date = response['current_month_data'][0].month_start_date;
          var month_end_date = response['current_month_data'][0].month_end_date;
          var pending_amount = response['pending_bachat_amount'][0].pending_bachat_amount;
          $("#collection_type").append("Bachat");
          $("#month_start_date").append(month_start_date);
          $("#month_end_date").append(month_end_date);
          $("#pending_amount").append(pending_amount);
        },
        error(data) {
          alert("error");
          // console.log(data);
        }
      });
    });

    $("#optionsRadios2").click(function() {
      var customer_id = document.getElementById("customer_id").value;
      if (customer_id == '') {
        alert('First Enter Customer Id.');
        document.getElementById("customer_id").style.borderColor = "red";
        document.getElementById("customer_id").focus();
        return false;

      }

      var url = window.location.origin + '/get_pending_loan/' + customer_id;
      $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',

        success: function(response) {

          $('#collection_type').empty();
          $('#month_start_date').empty();
          $('#month_end_date').empty();
          $('#pending_amount').empty();
          console.log(response['pending_loan_data'])
          if (response['pending_loan_data'].length != 0) {

            var pending_amount = response['pending_loan_data'][0].pending_loan;
            var month_start_date = response['pending_loan_data'][0].month_start_date;
            var month_end_date = response['pending_loan_data'][0].month_end_date;
            $("#collection_type").append("Loan");
            $("#month_start_date").append(month_start_date);
            $("#month_end_date").append(month_end_date);
            $("#pending_amount").append(pending_amount);
          } else {
            alert("This customer has not given any loan....")
          }
        },
        error(data) {
          alert("error");
          console.log(response);
        }
      });
    });

    function myconfirmation() {
      var customer_id = document.getElementById("customer_id").value;

      if (document.getElementById('optionsRadios1').checked) {

      } else if (document.getElementById('optionsRadios2').checked) {

      } else {
        alert('Select Collection Option First');
        return false;
      }

      if (customer_id == '') {
        alert('Enter Customer Id.');
        document.getElementById("customer_id").style.borderColor = "red";
        document.getElementById("customer_id").focus();
        return false;

      } else {
        var r = confirm("Are You Sure.");
        if (r == true) {
          return true;
        } else {
          return false;
        }
      }

    }
  </script>
  <script src="{{asset('lib/common-scripts.js')}}"></script>
  <script src="{{asset('lib/form-validation-script.js')}}"></script>


  <script type="text/javascript" src="{{asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('lib/advanced-form-components.js')}}"></script>

</body>



</html>