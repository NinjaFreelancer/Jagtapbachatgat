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
    <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->
    <div id="login-page">
        <div class="container">
            <div class="col-lg-12" style="margin-top: 250px;">
                <div class="row centered">
                    <div class="col-md-6" style="margin-bottom: 100px;">
                        <p>
                            <a href="{{url('/admin')}}" class="btn btn-theme">
                                <span>Admin</span>
                            </a>
                        </p>
                    </div>

                    <div class="col-md-6">
                        <p>
                            <a href="{{url('/customer')}}" class="btn btn-theme">
                                <span>Customer</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <!-- /col-lg-12 -->
        </div>
    </div>
    </div>
    @include('script.script')

    <script>
    $.backstretch("img/login-bg.jpg", {
        speed: 500
    });
    </script>
</body>

</html>