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
        @include('customer.sidebar.sidebar')
        <!--sidebar end-->
        <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->
        <!--main content start-->
        <section class="container-fluid mt-5" id="main-content">
            <section class="wrapper">
                <!-- BASIC FORM VALIDATION -->
                <div class="row mt">

                    <div class="col-sm-12">
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
                </div>
                <div class="row mt">
                    <div class="col-lg-4 col-md-3">

                    </div>
                    <div class="col-lg-4 col-md-6 col-md-12">

                        <div class="form-panel">
                            <form role="form" class="form-horizontal style-form centered"
                                action="{{url('customer_update_profile')}}" method="post" oncopy="return false"
                                oncut="return false" onpaste="return false" enctype="multipart/form-data">
                                @csrf
                                <h3>Change profile</h3>
                                <br>

                                <div class="form-group">
                                    <label
                                        class="col-lg-3 col-md-3 col-sm-3 col-lg-offset-1 col-md-offset-1 col-sm-offset-1 control-label">Profile</label>
                                    <div class="col-lg-7 col-md-7 col-sm-7">
                                        <input type="file" class="default" name="photo" id="photo" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-6">
                                        <button class="btn btn-theme" type="submit">Upload</button>
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
    <!--script for this page-->
    <script src="{{asset('lib/form-validation-script.js')}}"></script>

</body>

</html>
