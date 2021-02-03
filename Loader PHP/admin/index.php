<?php  
$logged = false;

$erro = "";
include_once __DIR__ . '/../main/class.php';
if(isset($_POST['submit']))
{
	$obj = json_decode(Main::ADMIN($_POST['user'],$_POST['pass']));
	switch ($obj->response) {
	    	case '400':
	    	$erro = "This user doens't exists";
			break;
			case '401':
			$erro = "Wrong password";
			break;
			case '402':
			$erro = "User banned";
			break;
			case '403':
			$erro = "This user is not premium";
			break;			
			case '406':
			$erro = "Subscription expired";
			break;		
			case '407':
			$erro = "This user is not an administrator";
			break;	
			case '3':
			$logged = true;
			break;	
			default:
			$erro = "Unknow error";	
			break;
	}
}

?>

<?php if(!$logged): ?>
<!DOCTYPE html>
<html>
<head>
	<title>LOGIN</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
</head>
<body>
	<form action="" method="POST">
		<center>
			<div class="box-text">
				<h1 class="title" style="color: #276CDA;">LOGIN</h1>
				<br>
				<input class="input is-rounded" name="user" type="text" placeholder="Username">
				<br><br>
				<input class="input is-rounded" name="pass" type="password" placeholder="Password">
				<br><br>
				<input class="button is-link is-fullwidth" type="submit" name="submit" value="LOGIN"></input>
				<br>
				<h6 class="subtitle is-6" style="color: red;"><?php echo $erro; ?></h6>
			</div>
		</center>
	</form>
</body>
<style type="text/css">
	.box-text{
		width: 300px;
		margin: 150px auto;
		font-family: 'Calibri';
	}
</style>
</html>
<?php endif ?>




<?php if($logged): ?>
<!DOCTYPE html>
<html>
<head>
	<title>PANEL</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.0/css/bulma.min.css">
	<script type="text/javascript" src="https://code.jquery.com/jquery-1.4.3.min.js"></script>
</head>
<body>
		<center>
			<div class="box" style="width: 400px;height: 460px; margin: 100px auto;">
				<h1 class="title" style="color: #276CDA;">ADMIN PANEL</h1>
				<br>
				<input class="input is-rounded" id="user"  type="text" placeholder="Username">
				<br><br>
				<div class="select is-rounded is-fullwidth">
				  <select id="type">				   
				    <option value="0">Normal user</option>
				    <option value="1">Premium user</option>
				    <option value="2">Admin user</option>
				  </select>
				</div>
				<br><br>
				Expiration
				<input class="input is-rounded" id="date" type="date" placeholder="Username">
				<br><br>
				<label class="checkbox"><input id="hwid" type="checkbox">Reset hwid</label>
				<label class="checkbox"><input id="ban" type="checkbox" >Ban user</label>
				<br><br>
				<button class="button is-link is-fullwidth" onclick="UPDATE()">UPDATE</button>
				<br>
				<h6 class="subtitle is-6" id="error" style="color: red;"></h6>
			</div>
		</center>
</body>
<script type="text/javascript">
	function UPDATE(){
		var user = $("#user").val();
		var type = $("#type").val();
		var date = $("#date").val();
		var hwid = $("#hwid").is(':checked');
		var ban = $("#ban").is(':checked');
		$.ajax({
			url: '/admin/update',
			type: 'post',
			async: true,
			data: `user=${user}&type=${type}&date=${date}&hwid=${hwid}&ban=${ban}`,
			success: function(data){
				var obj = JSON.parse(data);
				if(obj.response == "408"){
					$("#error").text("This user doens't exists");
					$("#error").css("color","red");
				}else if(obj.response == "4"){
					$("#error").text(`Username: ${user} updated`);
					$("#error").css("color","green");
				}				
			}
		});	
	}
	

</script>
<style type="text/css">
	.box-text{
		width: 300px;
		margin: 150px auto;
		font-family: 'Calibri';
	}
</style>
</html>
<?php endif ?>