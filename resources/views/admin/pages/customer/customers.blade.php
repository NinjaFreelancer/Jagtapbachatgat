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
    <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
    <!--main content start-->
    <section class="container-fluid" id="main-content">
      <section class="wrapper">
        <h3>Customer List</h3>
        <div class="row mb">
          <!-- page start-->
          <div class="content-panel">
            <div class="adv-table" style="overflow-x:auto;">
              <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered"
                id="hidden-table-info">
                <thead>
                  <tr>
                    <th>Account No</th>
                    <th>Cust Id</th>
                    <th>Name</th>
                    <th>Mobile No</th>
                    <th>Password</th>
                    <th>Select</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($customer_list as $customer_data)
                  <tr>
                    <td>{{$customer_data->acc_no}}
                      @if($customer_data->is_active != 1)
                      <span
                        style="height:8px;width:8px;background-color: red;border-radius: 50%;display: inline-block;"></span>
                      @elseif($customer_data->is_all_months_status_ready == 0 || $customer_data-> is_under_maintenance
                      == 1)
                      <span
                        style="height:8px;width:8px;background-color: #FFCC00;border-radius: 50%;display: inline-block;"></span>
                      @else
                      <span
                        style="height:8px;width:8px;background-color: #33F508;border-radius: 50%;display: inline-block;"></span>
                      @endif
                    </td>
                    <td>{{$customer_data->id}}</td>
                    <td>{{$customer_data->full_name}}</td>
                    <td>{{$customer_data->mobile_no}}</td>
                    <td>{{$customer_data->pass}}</td>
                    <td>
                      @if(($customer_data->is_account_ready_to_reuse) == 1)
                      <a href="{{url('/reuse_account/'.$customer_data->acc_no)}}" class="btn btn-theme">
                        <span>Reuse Account</span>
                      </a>
                      @else
                      <a href="{{url('/profile/'.$customer_data->id)}}" class="btn btn-theme">
                        <span>Profile</span>
                      </a>
                      @endif
                    </td>
                  </tr>
                  @endforeach

                </tbody>
              </table>
            </div>
          </div>
          <!-- page end-->
        </div>
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
</body>



</html>