<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Tardy (Grouped)</title>
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

			tr:nth-child(even) {
			  	background-color: #dddddd;
			}
		</style>
	</head>
	<body>
		<center><h3>Tardy (Grouped)</h3></center>
		<?php   

		if (sizeof($tardy_array) > 0) {
			?>
			<table>
				<tr>
				    <th>Student ID</th>
				    <th>Student Name</th>
				    <th>Grade</th>
				    <th>Number of Tardies</th>
				    <th>Tardy Count Per Period</th>
			  	</tr>
			  	<?php  
			  	foreach ($tardy_array as $tardy) {
			  		?>
			  		<tr>
					    <td><?php  echo $tardy['student_id']; ?></td>
					    <td><?php  echo $tardy['student_name']; ?></td>
					    <td><?php  echo $tardy['grade']; ?></td>
					    <td><?php  echo $tardy['tardy_count']; ?></td>
					    <td><?php  echo $tardy['period_tardy_count']; ?></td>
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