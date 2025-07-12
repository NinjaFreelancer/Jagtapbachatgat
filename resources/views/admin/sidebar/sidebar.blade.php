<body>
  <!-- **********************************************************************************************************************************************************
        TOP BAR CONTENT & NOTIFICATIONS
        *********************************************************************************************************************************************************** -->
  <!--header start-->

  <header class="header black-bg">
    <div class="sidebar-toggle-box">
      <div id="sidebar_button" class="fa fa-bars tooltips" data-placement="right"></div>
    </div>
    <!--logo start-->
    <a href="{{url('dashboard')}}" class="logo"><b>{{ Session::get('bachatgatname') }} BACHAT<span>GAT</span></b></a>
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
        <li><a class="logout" href="{{url('logout_admin')}}">Logout</a></li>
      </ul>
    </div>
  </header>

  <!--sidebar-->
  <!-- **********************************************************************************************************************************************************
        MAIN CONTENT
        *********************************************************************************************************************************************************** -->

  <aside>
    <div id="sidebar" class="nav-collapse ">
      <ul class="sidebar-menu" id="nav-accordion">
        <div class="profile-pic">
          <p class="centered"><a href="{{url('dashboard')}}"><img src="{{asset('img/admin/Jagtap.jpeg')}}"
                class="img-circle" width="80"></a>
          </p>
        </div>
        <h5 class="centered">Jagtap</h5>
        <li class="mt">
          <a href="{{url('dashboard')}}">
            <i class="fa fa-home"></i>
            <span>Dashboard</span>
          </a>
        </li>
        <!-- <li>
          <a href="{{url('collection')}}">
            <i class="fa fa-exchange"></i>
            <span>Collect Collection</span>
          </a>
        </li> -->
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-money"></i>
            <span>Collect Collection</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('collection')}}">
                <i class="fa fa-money"></i>
                <span>Daily Collection</span>
              </a>
            </li>
            <li><a href="{{url('missed_bachat_collection')}}">
                <i class="fa fa-money"></i>
                <span>Missed Collection</span>
              </a>
            </li>
            <!-- <li><a href="{{url('pending_loans')}}">
                                <i class="fa fa-file-text-o"></i>
                                <span>Pending Loan's</span>
                            </a>
                        </li> -->
          </ul>
        </li>
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-desktop"></i>
            <span>Collection Overview</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('todays_collection')}}">
                <i class="fa fa-file-text-o"></i>
                <span>Date-Wise Collection</span>
              </a>
            </li>
            <li><a href="{{url('current_month_collection')}}">
                <i class="fa fa-file-text-o"></i>
                <span>Month-Wise Collection</span>
              </a>
            </li>
            <!-- <li><a href="{{url('pending_loans')}}">
                                <i class="fa fa-file-text-o"></i>
                                <span>Pending Loan's</span>
                            </a>
                        </li> -->
          </ul>
        </li>
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-money"></i>
            <span>Fixed Deposit</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('add_fixed_deposit')}}">
                <i class="fa fa-plus-circle"></i>
                <span>Add</span>
              </a>
            </li>
            <li><a href="{{url('show_active_fd_statement')}}">
                <i class="fa fa-calendar"></i>
                <span>Active FD</span>
              </a>
            </li>
            <li><a href="{{url('show_history_of_fd_statement')}}">
                <i class="fa fa-calendar"></i>
                <span>History</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-exchange"></i>
            <span>Expenses</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('add_expense')}}">
                <i class="fa fa-plus-circle"></i>
                <span>Add</span>
              </a>
            </li>
            <li><a href="{{url('show_expenses')}}">
                <i class="fa fa-calendar"></i>
                <span>Show Expenses</span>
              </a>
            </li>
          </ul>
        </li>
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-user"></i>
            <span>Customer</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('new_cust')}}">
                <i class="fa fa-user-plus"></i>
                <span>Add</span>
              </a>
            </li>
            <li><a href="{{url('customers')}}">
                <i class="fa fa-users"></i>
                <span>List</span>
              </a>
            </li>
          </ul>
        </li>
        <!-- <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-user"></i>
                        <span>Collection Collector</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{url('add_new_cc')}}">
                                <i class="fa fa-user-plus"></i>
                                <span>Add</span>
                            </a>
                        </li>
                        <li><a href="{{url('collection_collectors')}}">
                                <i class="fa fa-users"></i>
                                <span>List</span>
                            </a>
                        </li>
                    </ul>
                </li> -->
        <li class="sub-menu">
          <a href="javascript:;">
            <i class="fa fa-cogs"></i>
            <span>Settings</span>
          </a>
          <ul class="sub">
            <li><a href="{{url('change_pin')}}">
                <i class="fa fa-key"></i>
                <span>Change Pin</span>
              </a>
            </li>
            <li><a href="{{url('change_pass')}}">
                <i class="fa fa-key"></i>
                <span>Change Password</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </aside>
</body>