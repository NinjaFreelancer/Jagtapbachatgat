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
        <section id="main-content">
            <section class="wrapper container-fluid">
                <div class="row mt">
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
                <div class="row mt">
                    <div class="col-lg-3 col-md-2 col-md-12">
                    </div>
                    <div class="col-lg-6 col-md-8 col-md-12">
                        <div class="form-panel">
                            <form role="form" class="form-horizontal style-form centered"
                                onsubmit="return myconfirmation()" action="{{url('customer_profile')}}" method="post"
                                oncopy="return false" oncut="return false" onpaste="return false"
                                enctype="multipart/form-data">
                                @csrf
                                <h3>Open Profile</h3>
                                <br>
                                <br>
                                <div class="form-group">
                                    <label
                                        class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 control-label">Account
                                        Number</label>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="text" placeholder="Enter Account No" id="acc_no" name="acc_no"
                                            class="form-control" style="text-transform: uppercase" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <button class="btn btn-theme" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /form-panel -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="user-count-panel">
                            <div class="panel-header">
                                <h5>Total Users</h5>
                            </div>
                            <h1><i class="fa fa-user fa-1x"></i></h1>

                            <h2 style="color: white; font-weight: 200;"> {{ $totalUsers }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="active-user-panel">
                            <div class="panel-header">
                                <h5>Total Active Accounts</h5>
                            </div>
                            <h1><i class="fa fa-user fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalActiveAccounts }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="collection-panel">
                            <div class="panel-header">
                                <h5>Total Collection</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalCollection }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="disbursed-loan-panel">
                            <div class="panel-header">
                                <h5>Total Disbursed Loan</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalDisbursedLoan }} </h2>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="row mt">
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="collection-panel">
                            <div class="panel-header">
                                <h5>Total Pending Loan</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalPendingLoan }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="disbursed-loan-panel">
                            <div class="panel-header">
                                <h5>Total Recovered Loan</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalRecoveredLoan }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="collected-interest-panel">
                            <div class="panel-header">
                                <h5>Total Collected Interest</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalCollectedInterest }} </h2>
                            <br>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-md-12 mt">
                        <div class="collected-interest-panel">
                            <div class="panel-header">
                                <h5>Total Collected Penalty</h5>
                            </div>
                            <h1><i class="fa fa-money fa-1x"></i></h1>
                            <h2 style="color: white;"> {{ $totalCollectedPenalty }} </h2>
                            <br>
                        </div>
                    </div>
                </div>
            </section>
        </section>

        @include('footer.footer')
        <script src="{{asset('lib/common-scripts.js')}}"></script>

        <!--footer end-->
    </section>
    <!-- <script type="text/javascript">
    $(document).ready(function() {
        var unique_id = $.gritter.add({
            // (string | mandatory) the heading of the notification
            title: 'Welcome to Dashio!',
            // (string | mandatory) the text inside the notification
            text: 'Hover me to enable the Close Button. You can hide the left sidebar clicking on the button next to the logo.',
            // (string | optional) the image to display on the left
            image: 'img/ui-sam.jpg',
            // (bool | optional) if you want it to fade out on its own or just sit there
            sticky: false,
            // (int | optional) the time you want it to be alive for before fading out
            time: 8000,
            // (string | optional) the class name you want to apply to that specific message
            class_name: 'my-sticky-class'
        });

        return false;
    });
    </script> -->
    <script type="application/javascript">
    $(document).ready(function() {
        $("#date-popover").popover({
            html: true,
            trigger: "manual"
        });
        $("#date-popover").hide();
        $("#date-popover").click(function(e) {
            $(this).hide();
        });

        $("#my-calendar").zabuto_calendar({
            action: function() {
                return myDateFunction(this.id, false);
            },
            action_nav: function() {
                return myNavFunction(this.id);
            },
            ajax: {
                url: "show_data.php?action=1",
                modal: true
            },
            legend: [{
                    type: "text",
                    label: "Special event",
                    badge: "00"
                },
                {
                    type: "block",
                    label: "Regular event",
                }
            ]
        });
    });

    function myNavFunction(id) {
        $("#date-popover").hide();
        var nav = $("#" + id).data("navigation");
        var to = $("#" + id).data("to");
        console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
    }

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
