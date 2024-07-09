<?php

//chart1.php

include('header.php');

$present_percentage = 0;
$absent_percentage = 0;
$total_present = 0;
$total_absent = 0;
$output = "";

$query = "
SELECT * FROM tbl_attendance 
INNER JOIN tbl_student  
ON tbl_student.student_id = tbl_attendance.student_id 
INNER JOIN tbl_grade 
ON tbl_grade.grade_id = tbl_student.student_grade_id 
WHERE tbl_student.student_grade_id = '".$_GET['grade_id']."' 
AND tbl_attendance.attendance_date = '".$_GET["date"]."'
";
//echo $query;
$statement = $connect->prepare($query);
$statement->execute();

$result = $statement->fetchAll();

$total_row = $statement->rowCount();

foreach($result as $row)
{
 $status = '';
 if($row["attendance_status"] == "Present")
 {
  $total_present++;
  $status = '<span class="badge badge-success">Present</span>';
 }

 if($row["attendance_status"] == "Absent")
 {
  $total_absent++;
  $status = '<span class="badge badge-danger">Absent</span>';
 }
 $output .= '
  <tr>
   <td>'.$row["student_name"].'</td>
   <td>'.$status.'</td>
  </tr>
 ';
}

if($total_row > 0)
{
 $present_percentage = ($total_present / $total_row) * 100;
 $absent_percentage = ($total_absent / $total_row) * 100;
}

?>

<div class="container" style="margin-top:30px">
  <div class="card">
   <div class="card-header"><b>Attendance Chart</b></div>
   <div class="card-body">
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <tr>
            <th>Grade Name</th>
            <td><?php echo Get_grade_name($connect, $_GET["grade_id"]); ?></td>
          </tr>
          <tr>
            <th>Date</th>
            <td><?php echo $_GET["date"]; ?></td>
          </tr>
        </table>
      </div>
    <div id="attendance_pie_chart" style="width: 100%; height: 400px;">

    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <tr>
            <th>Student Name</th>
            <th>Attendance Status</th>
          </tr>
          <?php 
          echo $output;
          ?>
      </table></div>
   </div>
  </div>
</div>

</body>
</html>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart()
  {
    var data = google.visualization.arrayToDataTable([
      ['Attendance Status', 'Percentage'],
      ['Present', <?php echo $present_percentage; ?>],
      ['Absent', <?php echo $absent_percentage; ?>]
    ]);

    var options = {
      title: 'Overall Attendance Analytics',
      hAxis: {
        title: 'Percentage',
        minValue: 0,
        maxValue: 100
      },
      vAxis: {
        title: 'Attendance Status'
      }
    };

    var chart = new google.visualization.PieChart(document.getElementById('attendance_pie_chart'));
    chart.draw(data, options);
  }
</script>


admin/report.php


<?php

//report.php
if(isset($_GET["action"]))
{
 include('database_connection.php');
 require_once 'pdf.php';
 session_start();
 $output = '';
 if($_GET["action"] == "student_report")
 {
  if(isset($_GET["student_id"], $_GET["from_date"], $_GET["to_date"]))
  {   
   $pdf = new Pdf();
   $query = "
   SELECT * FROM tbl_student 
   INNER JOIN tbl_grade 
   ON tbl_grade.grade_id = tbl_student.student_grade_id 
   WHERE tbl_student.student_id = '".$_GET["student_id"]."' 
   ";
   $statement = $connect->prepare($query);
   $statement->execute();
   $result = $statement->fetchAll();
   foreach($result as $row)
   {
    $output .= '
    <style>
    @page { margin: 20px; }
    
    </style>
    <p>&nbsp;</p>
    <h3 align="center">Attendance Report</h3><br /><br />
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
           <tr>
               <td width="25%"><b>Student Name</b></td>
               <td width="75%">'.$row["student_name"].'</td>
           </tr>
           <tr>
               <td width="25%"><b>Roll Number</b></td>
               <td width="75%">'.$row["student_roll_number"].'</td>
           </tr>
           <tr>
               <td width="25%"><b>Grade</b></td>
               <td width="75%">'.$row["grade_name"].'</td>
           </tr>
           <tr>
            <td colspan="2" height="5">
             <h3 align="center">Attendance Details</h3>
            </td>
           </tr>
           <tr>
            <td colspan="2">
             <table width="100%" border="1" cellpadding="5" cellspacing="0">
              <tr>
               <td><b>Attendance Date</b></td>
               <td><b>Attendance Status</b></td>
              </tr>
    ';
    $sub_query = "
    SELECT * FROM tbl_attendance 
    WHERE student_id = '".$_GET["student_id"]."' 
    AND (attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."') 
    ORDER BY attendance_date ASC
    ";
    $statement = $connect->prepare($sub_query);
    $statement->execute();
    $sub_result = $statement->fetchAll();
    foreach($sub_result as $sub_row)
    {
     $output .= '
      <tr>
       <td>'.$sub_row["attendance_date"].'</td>
       <td>'.$sub_row["attendance_status"].'</td>
      </tr>
     ';
    }
    $output .= '
      </table>
     </td>
     </tr>
    </table>
    ';
    $file_name = 'Attendance Report.pdf';
    $pdf->loadHtml($output);
    $pdf->render();
    $pdf->stream($file_name, array("Attachment" => false));
    exit(0);
   }
  }
 }

 if($_GET["action"] == "attendance_report")
 {
  if(isset($_GET["grade_id"], $_GET["from_date"], $_GET["to_date"]))
  {
   $pdf = new Pdf();
   $query = "
   SELECT tbl_attendance.attendance_date FROM tbl_attendance 
   INNER JOIN tbl_student 
   ON tbl_student.student_id = tbl_attendance.student_id 
   WHERE tbl_student.student_grade_id = '".$_GET["grade_id"]."' 
   AND (tbl_attendance.attendance_date BETWEEN '".$_GET["from_date"]."' AND '".$_GET["to_date"]."')
   GROUP BY tbl_attendance.attendance_date 
   ORDER BY tbl_attendance.attendance_date ASC
   ";
   $statement = $connect->prepare($query);
   $statement->execute();
   $result = $statement->fetchAll();
   $output .= '
    <style>
    @page { margin: 20px; }
    
    </style>
    <p>&nbsp;</p>
    <h3 align="center">Attendance Report</h3><br />';
   foreach($result as $row)
   {
    $output .= '
    <table width="100%" border="0" cellpadding="5" cellspacing="0">
           <tr>
            <td><b>Date - '.$row["attendance_date"].'</b></td>
           </tr>
           <tr>
            <td>
             <table width="100%" border="1" cellpadding="5" cellspacing="0">
              <tr>
               <td><b>Student Name</b></td>
               <td><b>Roll Number</b></td>
               <td><b>Grade</b></td>
               <td><b>Teacher</b></td>
               <td><b>Attendance Status</b></td>
              </tr>
       ';
       $sub_query = "
       SELECT * FROM tbl_attendance 
       INNER JOIN tbl_student 
       ON tbl_student.student_id = tbl_attendance.student_id 
       INNER JOIN tbl_grade 
       ON tbl_grade.grade_id = tbl_student.student_grade_id 
       INNER JOIN tbl_teacher 
       ON tbl_teacher.teacher_grade_id = tbl_grade.grade_id 
       WHERE tbl_student.student_grade_id = '".$_GET["grade_id"]."' 
    AND tbl_attendance.attendance_date = '".$row["attendance_date"]."'
       ";
       $statement = $connect->prepare($sub_query);
    $statement->execute();
    $sub_result = $statement->fetchAll();
    foreach($sub_result as $sub_row)
    {
     $output .= '
     <tr>
      <td>'.$sub_row["student_name"].'</td>
      <td>'.$sub_row["student_roll_number"].'</td>
      <td>'.$sub_row["grade_name"].'</td>
      <td>'.$sub_row["teacher_name"].'</td>
      <td>'.$sub_row["attendance_status"].'</td>
     </tr>
     ';
    }
    $output .= 
     '</table>
     </td>
     </tr>
    </table><br />';
   }
   $file_name = 'Attendance Report.pdf';
   $pdf->loadHtml($output);
   $pdf->render();
   $pdf->stream($file_name, array("Attachment" => false));
   exit(0);
  }
 }
}

?>
