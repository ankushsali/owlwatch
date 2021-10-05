<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Detention (Regular)</title>
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
		<center><h3>Detention (Regular)</h3></center>
		<?php   

		if (sizeof($detention_array) > 0) {
			?>
			<table>
				<tr style="background-color: #dddddd;">
				    <th>Date</th>
				    <th>Time</th>
				    <th>Student ID</th>
				    <th>Student Name</th>
				    <th>Reason</th>
				    <th>Comments</th>
			  	</tr>
			  	<?php  
			  	foreach ($detention_array as $detention) {
			  		?>
			  		<tr>
					    <td><?php  echo $detention['date']; ?></td>
					    <td><?php  echo $detention['time']; ?></td>
					    <td><?php  echo $detention['student_id']; ?></td>
					    <td><?php  echo $detention['student_name']; ?></td>
					    <td><?php  echo $detention['reason']; ?></td>
					    <td><?php  echo $detention['comments']; ?></td>
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