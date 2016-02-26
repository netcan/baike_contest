<meta charset="utf-8">
<?php
   session_start();
   include_once("functions.php");
   $user_id = trim($_POST['ID']);
   $user_password = trim($_POST['Password']);
   $db = connect_db();
   $sqlcmd = 'select id,name,password,phone,qid,qans,qmark,qright,status,qtime,qq from profile where id ='.$user_id.' and password = "'.$user_password.'"';
   $result = $db->query($sqlcmd);
   if($result->num_rows>0) {
      $profile = $result->fetch_array();

	    echo "欢迎登陆：".$profile[1];

		$_SESSION['User_id'] = $profile[0];
		$_SESSION['User_name'] = $profile[1];
		$_SESSION['User_password'] = $profile[2];
		$_SESSION['User_phone'] = $profile[3];
		if(!is_null($profile[4]))
		$_SESSION['User_qid'] = unserialize($profile[4]);
		else $_SESSION['User_qid'] = NULL;
		 
		if(!is_null($profile[5]))
		$_SESSION['User_qans'] = unserialize($profile[5]);
		else $_SESSION['User_qans'] = NULL;

		$_SESSION['User_qmark'] = $profile[6]+1;
		$_SESSION['User_qright'] = $profile[7];
		$_SESSION['User_status'] = $profile[8] ;
		$_SESSION['User_qtime'] = $profile[9] ;
		$_SESSION['User_qtime_start'] = 0;
		srand(time());
		$_SESSION['User_lid'] = rand(1,100000000);
		$_SESSION['User_qq'] = $profile[10];
		$sqlcmd = 'update profile set online='.USER_ONLINE.',lid='.$_SESSION['User_lid'].' where id='.$user_id;
		$db->query($sqlcmd);
		$result->close();
		$db->close();
		header("location:".INDEX);
	 }
	 else {
		$db->close();
		echo '
		<script language="JavaScript">
		   alert("登陆失败！请检查学号与密码！\n若无法登陆请联系化工创协\n");
		   history.go(-1);
		</script>
		';
  }

?>
