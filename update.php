<meta charset="utf-8">
<?php 
   include_once("functions.php");
   $id = trim($_POST['id']);
   $phone = trim($_POST['phone']);
   $password = trim($_POST['password']);
   $qq = trim($_POST['qq']);
   $db = connect_db();

   if($phone != "" && $qq == "")
   $sqlcmd = 'update profile set password=\''.$password.'\',phone='.$phone.',qq=null where id='.$id;
   if($phone == "" && $qq != "")
   $sqlcmd = 'update profile set password=\''.$password.'\',phone=null,qq='.$qq.' where id='.$id;
   if($phone != "" && $qq != "")
   $sqlcmd = 'update profile set password=\''.$password.'\',phone='.$phone.',qq='.$qq.' where id='.$id;
  // echo $sqlcmd;
   if($db->query($sqlcmd)) {
	  $_SESSION['User_password'] = $password;
	  $_SESSION['User_phone'] = $phone;
	  $_SESSION['User_qq'] = $qq;
	  $db->close();
	  echo '
	  	<script language="JavaScript">
		   alert("更新成功！\n请牢记账户信息！");
		   history.go(-1);
		   </script>
		';

   }
   else {
	  $db->close();
	  echo '
	  	<script language="JavaScript">
		   alert("修改失败！\n请联系管理员！\n");
		   history.go(-1);
		   </script>
		';
   }



?>
