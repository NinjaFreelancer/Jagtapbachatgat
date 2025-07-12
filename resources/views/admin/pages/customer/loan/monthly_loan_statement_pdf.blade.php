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
  <section class="container-fluid" style="background:white">
    <div class="row">
      <div class="col-md-1">
      </div>
      <div class="col-md-10">
        <section class="wrapper">
          <Div class="centered">
            <h3><b>Jagtap Bachatgat</b></h3>
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
            <h4>DETAILS OF LOAN STATEMENT</h4>
            @endforeach
            @endif
          </Div>
        </section>
        <section class="container-fluid">
          <div class="row mb">
            <!-- page start-->
            <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered">
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
            <!-- page end-->
          </div>
          <!-- /row -->
        </section>
      </div>
  </section>
  </div>

  <script src="{{asset('lib/common-scripts.js')}}"></script>

  <script type="text/javascript" language="javascript"
    src="{{asset('lib/advanced-datatable/js/jquery.dataTables.js')}}"></script>
  <script type="text/javascript" src="{{asset('lib/advanced-datatable/js/DT_bootstrap.js')}}"></script>
</body>


</html>