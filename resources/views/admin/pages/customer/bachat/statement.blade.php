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

    @include('admin.sidebar.sidebar')

    <section class="container-fluid" id="main-content">
      <section class="wrapper">
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
        <h3> Statement</h3>
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
                <h5>Account No : {{$cus_data->acc_no}}</h5>
                <br>
                <h4>DETAILS OF STATEMENT</h4>
                @endforeach
                @endif
              </Div>
              <br>
              <br>
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
                            <th>Transaction Date & Time</th>
                            <th>Collecton Date</th>
                            <th>Amount(Rs.)</th>
                            <th>Balance(Rs.)</th>
                            <th>Details</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if(!empty($statement_data))
                          @foreach ($statement_data as $data)
                          <tr>
                            <td>{{++$sr}}</td>
                            <td>{{$data->trans_date}} {{$data->trans_time}}</td>
                            <td>{{$data->collection_date}}</td>
                            <td>{{$data->amount}}{{$data->status}}</td>
                            <td>{{($balance=($balance + $data->amount))}}</td>
                            <td>{{$data->details}}</td>
                            <td>
                              <a onclick="return myconfirmation()" href="{{url('/cancel_transaction/'.$data->id)}}"
                                class="btn btn-danger btn-xs fa fa-trash-o">
                              </a>
                              <a onclick="return myconfirmation()" href="{{url('/modify_transaction/'.$data->id)}}"
                                class="btn btn-primary btn-xs fa fa-pencil">
                              </a>
                            </td>
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
                @if(!empty($customer_data))
                @foreach ($customer_data as $cus_data)
                @if(($cus_data->is_active)== 1)
                <div class="col-md-12 detailed">
                  <div class="row centered">
                    <div class="col-md-2">
                      <p>
                        <a href="{{url('/profile/'.$cus_data->id)}}" class="btn btn-theme">
                          <span>Profile</span>
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
                  </div>
                </div>
                @endif
                @endforeach
                @endif
              </section>
            </div>
          </div>
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