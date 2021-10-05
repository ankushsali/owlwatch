<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Detention (Grouped)</title>
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
		<center><h3>Detention (Grouped)</h3></center>
		<?php   

		if (sizeof($detention_array) > 0) {
			?>
			<table>
				<tr style="background-color: #dddddd;">
				    <th>Student ID</th>
				    <th>Student Name</th>
				    <th>Grade</th>
				    <th>Number of Detentions</th>
				    <th>Detention Count per Reason Type</th>
			  	</tr>
			  	<?php  
			  	foreach ($detention_array as $detention) {
			  		?>
			  		<tr>
					    <td><?php  echo $detention['student_id']; ?></td>
					    <td><?php  echo $detention['student_name']; ?></td>
					    <td><?php  echo $detention['grade']; ?></td>
					    <td><?php  echo $detention['detention_count']; ?></td>
					    <td><?php  echo $detention['detention_count']; ?></td>
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