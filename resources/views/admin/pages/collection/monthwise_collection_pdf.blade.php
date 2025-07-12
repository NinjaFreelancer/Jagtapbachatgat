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
        <section class="mt">
          <Div class="centered">
            <h2>Jagtap Bachatgat Collection</h2>
          </div>
          <h4>Date : {{$date}}</h4>
        </section>
        <section class="container-fluid">
          <div class="row">
            <!-- page start-->
            <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered">
              <thead>
                <tr>
                  <th>Account No</th>
                  <th>Name</th>
                  <th>Monthly Collection</th>
                  <th>Loan Collection</th>
                </tr>
              </thead>
              <tbody>
                @if($monthwise_collection != 0)
                @foreach ($monthwise_collection as $data)
                @if($data->coll != 0 || $data->loan != 0)
                <tr>
                  <td>{{$data->acc_no}}</td>
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
                @endif
                @endforeach
                @endif
              </tbody>
            </table>
            <!-- page end-->
            <!-- <label class="col-lg-8 col-md-8 control-label mt-5">
              <h3><b>Collection Details</b></h3>
              <h5><b>Total Bachat Collection :- {{$total_collection}}</b><span id="collection_type"></span>
              </h5>
              <h5><b>Total Loan Collection :- {{$total_loan_collection}}</b><span id="collection_type"></span>
              </h5>
              <h5><b>Total Collection :- {{$total_collection + $total_loan_collection}}</b><span
                  id="collection_type"></span>
              </h5>
            </label> -->
          </div>
          <!-- /row -->
        </section>
        <div class="row">
          <div class="col-md-4">
            <h4>Total Bachat Collection : {{$total_collection}}</h4>
          </div>
          <div class="col-md-4">
            <h4>Total Loan Collection : {{$total_loan_collection}}</h4>
          </div>
          <div class="col-md-4">
            <h4>Total Collection : {{$total_collection + $total_loan_collection}}</h4>
          </div>
        </div>
      </div>
  </section>
  </div>

  <script src="{{asset('lib/common-scripts.js')}}"></script>
  <script type="text/javascript" language="javascript"
    src="{{asset('lib/advanced-datatable/js/jquery.dataTables.js')}}"></script>
  <script type="text/javascript" src="{{asset('lib/advanced-datatable/js/DT_bootstrap.js')}}"></script>
</body>


</html>