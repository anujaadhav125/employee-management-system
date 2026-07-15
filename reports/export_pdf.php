<?php

require_once '../config/app.php';
require_once '../vendor/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

if (!isLoggedIn()) {
    redirect('../auth/login.php');
}

$query = "SELECT * FROM employees ORDER BY full_name ASC";
$result = mysqli_query($conn, $query);

$html = '

<h2 style="text-align:center;">StaffSync</h2>

<h3 style="text-align:center;">Employee Report</h3>

<table border="1" cellspacing="0" cellpadding="6" width="100%">

<tr style="background:#eeeeee;">

<th>Code</th>
<th>Name</th>
<th>Email</th>
<th>Department</th>
<th>Designation</th>
<th>Status</th>

</tr>

';

while($row = mysqli_fetch_assoc($result)){

$html .= '

<tr>

<td>'.$row['employee_code'].'</td>

<td>'.$row['full_name'].'</td>

<td>'.$row['email'].'</td>

<td>'.$row['department'].'</td>

<td>'.$row['designation'].'</td>

<td>'.$row['status'].'</td>

</tr>

';

}

$html .= '</table>';

$dompdf = new Dompdf();

$dompdf->loadHtml($html);

$dompdf->setPaper('A4','landscape');

$dompdf->render();

$dompdf->stream("Employee_Report.pdf",["Attachment"=>true]);

exit;