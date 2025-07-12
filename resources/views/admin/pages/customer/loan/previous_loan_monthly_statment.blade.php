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
      <section class="wrapper">
        <h3>Previous Loan Monthly Statement</h3>
        <div class="row">
          <div class="col-md-12">
            <div class="content-panel">
              <Div class="centered">
                <h3>Jagtap Bachatgat</h3>
                @if(!empty($customer_data))
                @foreach ($customer_data as $cus_data)
                @if(!empty(($cus_data->full_name)))
                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                @else
                <h4><b>Name : </b>{{$cus_data->shop_name}}</h4>
                <h4><b>Cust Name : </b>{{$cus_data->full_name}}</h4>
                @endif
                <h5>Cust Id : {{$cus_data->id}}</h5>
                <h5>Account No : {{$cus_data->acc_no}}</h5>
                <br>
                <h4>DETAILS OF MONTHLY STATEMENT</h4>
                @endforeach
                @endif
              </Div>
              <br>
              <br>

              <!-- <hr> -->
              <section class="container-fluid">
                <div class="row mb">
                  <!-- page start-->
                  <div class="content-panel">
                    <div class="adv-table" style="overflow-x:auto;">
                      <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered"
                        id="hidden-table-info">
                        <thead>
                          <tr>
                            <th>Sr.No</th>
                            <th>Amount Of Loan Paid Off</th>
                            <th>Pending Loan</th>
                            <th>Interest</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($monthly_loan_statement))
                          @foreach ($monthly_loan_statement as $data)
                          <tr>

                            <td>{{++$sr}}</td>
                            <td>{{$data->amount_of_loan_paid_off}}</td>
                            <td>{{$data->pending_loan}}</td>
                            <td>{{$data->interest}}</td>
                            <td>{{$data->month_start_date}}</td>
                            <td>{{$data->month_end_date}}</td>
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


                <br>
                <!-- /tab-pane -->
              </div>
              <!-- /tab-content -->
            </div>
            <!-- /panel-body -->
          </div>
          <!-- /col-lg-12 -->
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

    <!--script for this page-->
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
  </section>
</body>

</html>