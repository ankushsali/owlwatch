<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tardy (Regular)</title>
		<style>
			table {
			  	font-family: arial, sans-serif;
			  	border-collapse: collapse;
			  	width: 100%;
			}

			td, th {
			  	border: 1px solid #dddddd;
			  	text-align: left;
			  	padding: 8px;
			}

			/*tr:nth-child(even) {
			  	background-color: #dddddd;
			}*/
		</style>
	</head>
	<body>
		<center><h3>Tardy (Regular)</h3></center>
		<?php   

		if (sizeof($tardy_array) > 0) {
			?>
			<table>
				<tr style="background-color: #dddddd;">
				    <th>Date</th>
				    <th>Time</th>
				    <th>Period</th>
				    <th>Student ID</th>
				    <th>Student Name</th>
			  	</tr>
			  	<?php  
			  	foreach ($tardy_array as $tardy) {
			  		?>
			  		<tr>
					    <td><?php  echo $tardy['date']; ?></td>
					    <td><?php  echo $tardy['time']; ?></td>
					    <td><?php  echo $tardy['period']; ?></td>
					    <td><?php  echo $tardy['student_id']; ?></td>
					    <td><?php  echo $tardy['student_name']; ?></td>
				  	</tr>
			  		<?php
			  	}
			  	?>
			</table>
			<?php
		}else{
			?>
			<h3>Records not found!</h3>
			<?php
		}
		?>
	</body>
</html>