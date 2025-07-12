<header class="header black-bg">
    <div class="sidebar-toggle-box">
        <div id="sidebar_button" class="fa fa-bars tooltips" data-placement="right"></div>
    </div>
    <!--logo start-->
    <a href="index.html" class="logo"><b>TEST Jagtap BACHAT<span>GAT</span></b></a>
    <!--logo end-->
    <div class="top-menu">
        <ul class="nav pull-right top-menu">
            <!-- <div class="nav notify-row" id="top_menu"> -->
            <!--  notification start -->
            <!-- <ul class="nav top-menu"> -->
            <!-- notification dropdown start-->
            <!-- <li id="header_notification_bar" class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                                <i class="fa fa-bell-o"></i>
                                <span class="badge bg-warning">7</span>
                            </a>
                            <ul class="dropdown-menu extended notification">
                                <div class="notify-arrow notify-arrow-yellow"></div>
                                <li>
                                    <p class="yellow">You have 7 new notifications</p>
                                </li>
                                <li>
                                    <a href="index.html#">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        Server Overloaded.
                                        <span class="small italic">4 mins.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.html#">
                                        <span class="label label-warning"><i class="fa fa-bell"></i></span>
                                        Memory #2 Not Responding.
                                        <span class="small italic">30 mins.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.html#">
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        Disk Space Reached 85%.
                                        <span class="small italic">2 hrs.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.html#">
                                        <span class="label label-success"><i class="fa fa-plus"></i></span>
                                        New User Registered.
                                        <span class="small italic">3 hrs.</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="index.html#">See all notifications</a>
                                </li>
                            </ul>
                        </li> -->
            <!-- notification dropdown end -->
            <!-- </ul> -->
            <!--  notification end -->
            <!-- </div>  -->
            <li><a class="logout" href="{{url('customer_logout')}}">Logout</a></li>
        </ul>
    </div>
</header>

<!--sidebar-->
<!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->

<aside>
    <div id="sidebar" class="nav-collapse">
        <ul class="sidebar-menu mt-5" id="nav-accordion">
            @if(!empty($customer_data))
            @foreach ($customer_data as $cus_data)
            <div class="profile-pic">
                <p class="centered"><a href="{{asset('profile/'.$cus_data->profile)}}"><img
                            src="{{asset('profile/'.$cus_data->profile)}}" class="img-circle"></a>
                </p>
            </div>
            @endforeach
            @endif
            <!-- <h5 class="centered">Shivraj Jagtap</h5> -->
            <br>
            <li>
                <a href="{{url('customers_dashboard')}}">
                    <i class="fa fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{url('customer_statement')}}">
                    <i class="fa fa fa-calendar"></i>
                    <span>Bachat Statement</span>
                </a>
            </li>
            <li>
                <a href="{{url('customer_monthly_statement')}}">
                    <i class="fa fa-calendar-o"></i>
                    <span>Monthly Bachat Status</span>
                </a>
            </li>
            @if(Session::get('is_loan_active') == 1)
            <li>
                <a href="{{url('/customer_loan_statement')}}">
                    <i class="fa fa fa-calendar"></i>
                    <span>Loan Statement</span>
                </a>
            </li>
            <li>
                <a href="{{url('/customer_monthly_loan_statement')}}">
                    <i class="fa fa-calendar-o"></i>
                    <span>Monthly Loan Status</span>
                </a>
            </li>
            @endif
            <li>
                <a href="{{url('customers_change_profile')}}">
                    <i class="fa fa-key"></i>
                    <span>Change Profile</span>
                </a>
            </li>
            <li>
                <a href="{{url('customers_change_pass')}}">
                    <i class="fa fa-key"></i>
                    <span>Change Password</span>
                </a>
            </li>
        </ul>
    </div>
</aside>