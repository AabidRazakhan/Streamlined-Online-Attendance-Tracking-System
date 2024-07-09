<?php



include('admin/database_connection.php');

session_start();

if(isset($_SESSION["teacher_id"]))
{
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>SIRT Attendance System </title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <style>
    body {
      background: linear-gradient(90deg,rgba(2,0,.6,1)-15%,rgba(9,9,121,1)35%,rgba(0,212,255,1)100%); /* Medium gray background with transparency */
      color: #fff;
    }
    .container{
      background: transparent; /* Transparent jumbotron */
      color: #fff;
    }
    .jumbotron {
      background: transparent; /* Transparent jumbotron */
      color: #fff;
    }
    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      transition: transform 0.3s; /* Add transition for hover effect */
      background-color: rgba(108, 117, 125, 0.8); /* Medium gray card background with transparency */
      color: #fff;
    }
    .card:hover {
      transform: scale(1.05); /* Scale up on hover */
    }
    .btn-info {
      background-color: rgba(2,0,.6,1)-15%; /* Teal button with transparency */
      border-color: rgba(32, 201, 151, 0.7);
    }
    .btn-info:hover {
      background-color: rgba(23, 162, 184, 0.9); /* Slightly darker teal on hover with transparency */
      border-color: rgba(23, 162, 184, 0.9);
    }
    label {
      color: #fff; /* Label text color */
    }
    .text-danger {
      color: #dc3545; /* Error message text color */
    }
    
</style>
</head>


<body>


<div class="jumbotron text-center" style="margin-bottom:0";>
  <h1>SIRT Student Attendance System</h1>
  
</div>



<div class="container">
  <div class="row">
    <div class="col-md-4">

    </div>
    <div class="col-md-4" style="margin-top:20px;">
      <div class="card">
        <div class="card-header">Teacher Login</div>
        <div class="card-body">
          <form method="post" id="teacher_login_form">
            <div class="form-group">
              <label>Enter Email Address</label>
              <input type="text" name="teacher_emailid" id="teacher_emailid" class="form-control" />
              <span id="error_teacher_emailid" class="text-danger"></span>
            </div>
            <div class="form-group">
              <label>Enter Password</label>
              <input type="password" name="teacher_password" id="teacher_password" class="form-control" />
              <span id="error_teacher_password" class="text-danger"></span>
            </div>
            <div class="form-group">
              <input type="submit" name="teacher_login" id="teacher_login" class="btn btn-info" value="Login" />
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">

    </div>
  </div>
</div>

</body>
</html>

<script>
$(document).ready(function(){
  $('#teacher_login_form').on('submit', function(event){
    event.preventDefault();
    $.ajax({
      url:"check_teacher_login.php",
      method:"POST",
      data:$(this).serialize(),
      dataType:"json",
      beforeSend:function()
      {
        $('#teacher_login').val('Validate...');
        $('#teacher_login').attr('disabled', 'disabled');
      },
      success:function(data)
      {
        if(data.success)
        {
          location.href = "index.php";
        }
        if(data.error)
        {
          $('#teacher_login').val('Login');
          $('#teacher_login').attr('disabled', false);
          if(data.error_teacher_emailid != '')
          {
            $('#error_teacher_emailid').text(data.error_teacher_emailid);
          }
          else
          {
            $('#error_teacher_emailid').text('');
          }
          if(data.error_teacher_password != '')
          {
            $('#error_teacher_password').text(data.error_teacher_password);
          }
          else
          {
            $('#error_teacher_password').text('');
          }
        }
      }
    })
  });
});
</script>
