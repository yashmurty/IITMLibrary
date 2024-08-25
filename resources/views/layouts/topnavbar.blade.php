<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                IITM Library
            </a>
            <a href="{{ URL::route('home') }}">
                <img src="{{ URL::asset('img/IIT_Madras_Logo_300.png') }}" style="height:40px; margin-top:5px">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                @if (Auth::guest())

                @else
                <li><a href="{{ url('/home') }}">Home</a></li>
                <li><a href="{{ URL::route('bookrequisitionform') }}">Book Requisition Form</a></li>
                <li><a href="{{ URL::route('book-budget-department-view') }}"><i class="fa fa-btn fa-money"></i>| Book Budget</a></li>
                @if ($auth_usertype == "lac")
                <li><a href="{{ URL::route('lachome') }}">LAC Home</a></li>
                @elseif ($auth_usertype == "admin")
                <li><a href="{{ URL::route('adminhome') }}">Admin Home</a></li>
                @else
                <li><a href="{{ URL::route('requeststatus') }}">Request Status</a></li>
                @endif

                @endif
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                <li><a href="{{ url('/login') }}">Login</a></li>
                <!-- <li><a href="{{ url('/register') }}">Register</a></li> -->
                @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" style="padding:0px;" data-toggle="dropdown" role="button" aria-expanded="false">
                        <img src="https://photos.iitm.ac.in/byid.php?id={{ Auth::user()->iitm_id }}" style="height:50px;">
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a class="disabled"><i class="fa fa-btn fa-user"></i>Role: <span class="label label-default">{{$auth_usertype}}</span></a></li>
                        <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                    </ul>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>