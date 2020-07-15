<!DOCTYPE html>
<html>
<head>
	<title>PDF</title>
</head>
<body>
	   <h3>{{$customer->name}} details</h3>
		<center><table style="width: 100%;">
			<tr>
				<th>ID</th>
				<td>{{$customer->id}}</td>
			</tr>
			<tr>
				<th>Name</th>
				<td>{{$customer->name}}</td>
			</tr>
			<tr>
				<th>Age</th>
				<td>{{$customer->age}}</td>
			</tr>
			<tr>
				<th>Address</th>
				<td>{{$customer->address}}</td>
			</tr>
			<tr>
				<th>Phone</th>
				<td>{{$customer->phone}}</td>
			</tr>
		</table></center>
</body>
</html>