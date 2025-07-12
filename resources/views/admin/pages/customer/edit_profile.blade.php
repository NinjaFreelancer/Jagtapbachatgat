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
                            @if($data != 0)
                            @foreach ($customer_data as $cus_data)
                            <form role="form" class="form-horizontal style-form" onsubmit="return myconfirmation()"
                                action="{{url('update_customer_info/'.$cus_data->id)}}" method="post"
                                oncopy="return false" oncut="return false" onpaste="return false"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="centered">
                                    <h3>Updation Form</h3>
                                </div>
                                <br>
                                <div class="row form-group">
                                    <div class="col-lg-5 col-lg-offset-2">
                                        <label>Customer Id : {{$cus_data->id}}</label>
                                    </div>
                                    <div class="col-lg-5">
                                        <label>Account No : {{$cus_data->acc_no}}</label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Full Name</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->full_name}}" name="full_name"
                                                    id="full_name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Shop Name</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->shop_name}}" name="shop_name"
                                                    id="shop_name" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Mobile Number</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->mobile_no}}" name="mobile"
                                                    id="mobile" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Aaddhaar Number</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->aadhaar}}" name="aadhaar"
                                                    id="addhar" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">PAN Number</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->pan}}" name="pan" id="pan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Email</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->email}}" name="email" id="email"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-lg-4 control-label">Address</label>
                                            <div class="col-lg-8">
                                                <input type="text" value="{{$cus_data->address}}" name="address"
                                                    id="address" class="form-control" rows="4" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-lg-2 control-label">Password</label>
                                    <div class="col-lg-4">
                                        <input type="text" value="{{$cus_data->pass}}" name="pass" id="pass"
                                            class="form-control" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6 col-lg-offset-2">
                                        <button class="btn btn-theme" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                            @endforeach
                            @endif
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
    <script>
    function myconfirmation() {
        var name = document.getElementById("full_name").value;
        var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
        var mobile_no = document.getElementById("mobile").value;
        var address = document.getElementById("address").value;
        var pass = document.getElementById("pass").value;
        if (mobile_no.match(phoneno)) {
            if (aadhaar != '') {
                if (aadhaar.match(aadhaar_no)) {
                    alert("Entere Valid Aadhaar Number");
                    document.getElementById("aadhaar").style.borderColor = "red";
                    document.getElementById("aadhaar").focus();
                    return false;
                }
            }

            if (name == '') {
                alert('Enter The Full Name');
                document.getElementById("full_name").style.borderColor = "red";
                document.getElementById("full_name").focus();
                return false;
            } else if (email == '') {
                alert('Enter The Email Address');
                document.getElementById("email").style.borderColor = "red";
                document.getElementById("email").focus();
                return false;
            } else if (address == '') {
                alert('Enter The Address');
                document.getElementById("address").style.borderColor = "red";
                document.getElementById("address").focus();
                return false;
            } else if (pass == '') {
                alert('Enter The Password');
                document.getElementById("pass").style.borderColor = "red";
                document.getElementById("pass").focus();
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
    < /body>

        < /html>