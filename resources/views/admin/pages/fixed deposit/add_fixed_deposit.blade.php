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
                    <div class="col-lg-12">
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
                                action="{{url('submit_fixed_deposit')}}" method="post" oncopy="return false" oncut="return false"
                                onpaste="return false" enctype="multipart/form-data">
                                @csrf
                                <br>
                                <div class="centered">
                                    <h3> Fixed Deposit Form </h3>
                                </div>
                                <br>
                                <br>
                                <div class="row">
                                    <div class="col-lg-3">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Full Name</label>
                                            <div class="col-lg-8">
                                                <input type="text" placeholder="Enter The Full Name Of Customer" name="full_name" id="full_name"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Mobile Number</label>
                                            <div class="col-lg-8">
                                                <input type="text" placeholder="Enter Mobile Number" name="mobile" id="mobile"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Amount</label>
                                            <div class="col-lg-8">
                                                <input type="number" placeholder="Enter Fixed Deposit Amount" name="fixed_deposit_amount"
                                                    id="fixed_deposit_amount" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-4">Start Date</label>
                                            <div class="col-lg-4 col-xs-8">
                                                <div data-date-viewmode="years" data-date-format="dd-mm-yyyy" data-date="01-01-2022"
                                                    class="input-append date dpYears">
                                                    <input type="text" readonly="" size="16" class="form-control" name="date_of_deposit"
                                                        id="date_of_deposit">
                                                    <span class="input-group-btn add-on">
                                                        <button class="btn btn-theme" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                                <span class="help-block">Select date</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Pin</label>
                                            <div class="col-lg-8">
                                                <input type="password" placeholder="Enter Pin" name="con_pin" id="con_pin" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-lg-6">
                                                <button class="btn btn-theme" type="submit">Submit</button>
                                            </div>
                                        </div>
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
    <script src="{{asset('lib/common-scripts.js')}}"></script>
    <script src="{{asset('lib/form-validation-script.js')}}"></script>


    <script type="text/javascript" src="{{asset('lib/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script src="{{asset('lib/advanced-form-components.js')}}"></script>
    <script>
        function myconfirmation() {


            var name = document.getElementById("full_name").value;
            var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
            var mobile_no = document.getElementById("mobile").value;
            var aadhaar = document.getElementById("aadhaar").value;

            var pan = document.getElementById("pan").value;
            var date = document.getElementById("date").value;
            var email = document.getElementById("email").value;
            var bachat_amount = document.getElementById("bachat_amount").value;
            var address = document.getElementById("address").value;


            var photo = document.getElementById("photo").value;

            if (mobile_no.match(phoneno)) {

                if (name == '') {
                    alert('Enter The Full Name');
                    document.getElementById("full_name").style.borderColor = "red";
                    document.getElementById("full_name").focus();
                    return false;
                } else if (aadhaar == '') {
                    alert("Entere Valid Aadhaar Number");
                    document.getElementById("aadhaar").style.borderColor = "red";
                    document.getElementById("aadhaar").focus();
                    return false;
                } else if (pan == '') {
                    alert('Enter The Pan Number');
                    document.getElementById("pan").style.borderColor = "red";
                    document.getElementById("pan").focus();
                    return false;
                } else if (date == '') {
                    alert('Select Date');
                    document.getElementById("date").style.borderColor = "red";
                    document.getElementById("date").focus();
                    return false;
                } else if (email == '') {
                    alert('Enter The Email Address');
                    document.getElementById("email").style.borderColor = "red";
                    document.getElementById("email").focus();
                    return false;
                } else if (bachat_amount == '') {
                    alert('Enter The Bachat Amount');
                    document.getElementById("bachat_amount").style.borderColor = "red";
                    document.getElementById("bachat_amount").focus();
                    return false;
                } else if (address == '') {
                    alert('Enter The Address');
                    document.getElementById("address").style.borderColor = "red";
                    document.getElementById("address").focus();
                    return false;
                } else if (photo == '') {
                    alert('Select Profile');
                    document.getElementById("photo").style.borderColor = "red";
                    document.getElementById("photo").focus();
                    return false;
                } else if (pass == '') {
                    alert('Enter The Password');
                    document.getElementById("pass").style.borderColor = "red";
                    document.getElementById("pass").focus();
                    return false;
                } else if (con_pass == '') {
                    alert('Enter The Confirm Password');
                    document.getElementById("con_pass").style.borderColor = "red";
                    document.getElementById("con_pass").focus();

                    return false;
                } else {
                    var r = confirm("Are You Sure.");
                    if (r == true) {
                        return true;
                    } else {
                        return false;
                    }
                }
            } else {
                alert("Entere Valid Mobile Number");
                document.getElementById("mobile").style.borderColor = "red";
                document.getElementById("mobile").focus();
                return false;
            }
        }
    </script>
</body>


</html>