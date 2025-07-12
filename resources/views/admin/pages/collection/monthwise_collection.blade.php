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
        <div style="display: flex;
            justify-content: flex-end;">
          <a href="{{url('/mothwise_collection_pdf/'.$date)}}" class="btn btn-danger"><span>PDF</span></a>
        </div>
        <h3>Collection Report</h3>
        <div class="row content-panel">
          <div class="row">
            <div class="col-md-4">
              <h5>Date : {{$date}}</h5>
            </div>
            <div class="border">
              <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                action="{{url('monthwise_collection')}}" method="post" oncopy="return false" oncut="return false"
                onpaste="return false" enctype="multipart/form-data">
                @csrf
                <div class="col-lg-2 col-md-2">
                  <label class="control-label">Collection For</label>
                </div>
                <div class="col-lg-2 col-md-2 col-xs-6 ml">
                  <div data-date-viewmode="years" data-date-format="mm-yyyy" data-date="01-2020"
                    class="input-append date dpYears">
                    <input type="text" readonly="" size="16" class="form-control" name="collection_for"
                      id="collection_for">
                    <span class="input-group-btn add-on">
                      <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                    </span>
                  </div>
                  <span class="help-block">Select date</span>
                </div>
                <div class="col-lg-offset-1 col-md-offset-1 col-lg-2 col-md-2">
                  <button class="btn btn-theme" type="submit">Submitt</button>
                </div>
              </form>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-md-3">
              <h5>Total Monthly Collection : {{$total_collection}}</h5>
            </div>
            <div class="col-md-3">
              <h5>Total Loan Monthly Collection : {{$total_loan_collection}}</h5>
            </div>
            <div class="col-md-3">
              <h5>Total Collection : {{$total_collection + $total_loan_collection}}</h5>
            </div>
          </div>
        </div>
      </section>
      <section class="container-fluid">
        <div class="row mb">
          <!-- page start-->
          <div class="content-panel">
            <div class="adv-table" style="overflow-x:auto;">
              <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered"
                id="hidden-table-info">
                <thead>
                  <tr>
                    <th>Account No</th>
                    <th>Name</th>
                    <th>Daily Collection</th>
                    <th>Loan Collection</th>
                  </tr>
                </thead>
                <tbody>
                  @if($datewise_collection != 0)
                  @foreach ($datewise_collection as $data)
                  <tr>
                    <td>{{$data->acc_no}}
                      @if(($data->is_active) == 1)
                      <span
                        style="height:8px;width:8px;background-color: #33F508;border-radius: 50%;display: inline-block;"></span>
                      @else
                      <span
                        style="height:8px;width:8px;background-color: red;border-radius: 50%;display: inline-block;"></span>

                      @endif
                    </td>
                    <td>{{$data->full_name}}</td>
                    @if(($data->coll) == NULL)
                    <td>0</td>
                    @else
                    <td>{{$data->coll}}</td>
                    @endif

                    @if(($data->loan) == NULL)
                    <td>0</td>
                    @else
                    <td>{{$data->loan}}</td>
                    @endif
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
  <script type="text/javascript" src="{{asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
  <script src="{{asset('lib/advanced-form-components.js')}}"></script>
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