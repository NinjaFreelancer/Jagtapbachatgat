<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mybhumi</title>
  <style>
  body {
    min-width: 992px !important;
    font-size: 17px;
    min-height: 50% !important;
  }

  .card {
    background: #faf5f5;
    padding: 15px;
    font-size: 0.9rem;
    /* letter-spacing: 0.1rem; */
  }

  p {
    text-align: justify;
  }
  </style>

</head>

<body>

  <div style="padding: 20px 20px 20px 20px;background: #fff">
    <h2>Welcome</h2>
    <hr>
    <h3> Hi ,</h3>
    <p> Welcome to Jagtap Bachat Gat! <br> It's nice to meet you. Thank you for the recent collection. <br>
      your a/c {{ Session::get('acc_no') }} added Rs.{{ Session::get('conn_amount') }}
      of {{ Session::get('collection_for') }} at
      {{ Session::get('date') }} {{ Session::get('time') }}.
    </p>

    <p>
      <b> <a href="{{url('customer')}}">Click Here</a> </b> to Login on customer Dashboard
      and check your account
    </p>
    <p>
      We appreciate your interest in Jagtap Bachat Gat and wish you the best of luck in your Plan.
    </p>
    <p style="text-align: justify;text-align: left">
      Sincerely, <br>
      Jagtap Bachat Gat Recruiting Team <br>
      www.jagtapbachatgat.in

    </p>
    <hr>
    <div style="padding: 0%;">
      <h5 align="center" style="color:red;font-weight: 500">Do Not Replay This Email</h5>
      <!-- <h5 align="center"><a href="#">Click Here</a> For More Details</h5> -->
    </div>
  </div>
</body>

</html>