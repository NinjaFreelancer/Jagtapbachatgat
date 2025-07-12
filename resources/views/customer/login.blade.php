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
            <form class="form-login" action="{{url('customer_login')}}" method="post" oncopy="return false"
                oncut="return false" onpaste="return false">
                @csrf
                <h2 class="form-login-heading">Customer <br> <br> sign in now</h2>
                <div class="login-wrap">
                    <input type="text" class="form-control" name="acc_no" placeholder="Account Number" autofocus>
                    <br>
                    <input type="password" class="form-control" name="password" placeholder="Password">
                    <br>
                    <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i>
                        Log In</button>
                    <br>
                    <p>Are you admin ? <a href="{{url('admin')}}"> Admin </a></p>
                </div>
                <div class="showback">
                    @if(Session::has('error'))
                    <p class="alert alert-danger">
                        {{ Session::get('error') }}
                    </p>
                    {{ Session::forget('error') }}
                    @endif
                </div>
            </form>
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