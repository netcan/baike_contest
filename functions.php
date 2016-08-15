<?php
include_once 'include/common.inc';
@session_start();
// 在线人数，提交人数
$db = connect_db();
function get_online_user()
{
    global $db;
    $runtime = $db->query('select runtime,online_runtime from core')->fetch_array();
    if (time() - $runtime[0] > 60) {
        $sqlcmd = 'update core set runtime=' . time();
        $db->query($sqlcmd);
        if (is_login()) {
            $sqlcmd = 'select lid from profile where id=' . $_SESSION['User_id'];
            $result = $db->query($sqlcmd);
            $lid = $result->fetch_array();
            $result->close();
            if ($_SESSION['User_lid'] != $lid[0]) {
                echo '
			<script language="JavaScript">
			   alert("你已被挤下线！\\n");
		   </script>
			';
                update_db(false);
                unset_session();
            }
        }
    }
    if (time() - $runtime[1] > 900) {
        $sqlcmd = 'update core set online_runtime=' . time();
        $db->query($sqlcmd);
        $db->query('update profile set online=0');
    }
    if (is_login()) {
        update_db(true);
    }
    $sqlcmd = 'select online from profile where online=1';
    $user_online_stat[0] = $db->query($sqlcmd)->num_rows;
    $sqlcmd = 'select status from profile where status=1';
    $user_online_stat[1] = $db->query($sqlcmd)->num_rows;
    return $user_online_stat;
}
// 头部信息
function head($title)
{
    echo '
<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="img/Logo.png">
	<title>百科竞赛平台|' . $title . '</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap-combined.min.css" rel="stylesheet">

   <link href="css/layoutit.css" rel="stylesheet">
    <link rel="stylesheet" href="css/title.css" media="screen" type="text/css" />

    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->


	<style type="text/css">
.Logo img{
    width:64px; /*设置图像的长和宽，这里要根据自己的评论框情况更改*/
    border-radius: 50%;/*设置图像圆角效果,在这里我直接设置了超过width/2的像素，即为圆形了*/
    -webkit-border-radius: 50%;/*圆角效果：兼容webkit浏览器*/
    -moz-border-radius:50%;
    box-shadow: inset 0 -1px 0 #3333sf;/*设置图像阴影效果*/
    -webkit-box-shadow: inset 0 -1px 0 #3333sf;
    -webkit-transition: 0.4s;
    -webkit-transition: -webkit-transform 0.4s ease-out;
    transition: transform 0.4s ease-out;/*变化时间设置为0.4秒(变化动作即为下面的图像旋转360读）*/
    -moz-transition: -moz-transform 0.4s ease-out;
}
.Logo img:hover{/*设置鼠标悬浮在头像时的CSS样式*/
    box-shadow: 0 0 10px #fff; rgba(255,255,255,.6), inset 0 0 20px rgba(255,255,255,1);
    -webkit-box-shadow: 0 0 10px #fff; rgba(255,255,255,.6), inset 0 0 20px rgba(255,255,255,1);
    transform: rotateZ(360deg);/*图像旋转360度*/
    -webkit-transform: rotateZ(360deg);
    -moz-transform: rotateZ(360deg);
 }
 </style>
  </head>
  <body onload="clockon(bgclock)"  onselectstart="return false" oncontextmenu="return false">


<div style="text-align:center;clear:both;position:absolute;top:0;left:260px">
</div>
<canvas class="canvas"></canvas>

<div class="help" style="display: none">?</div>
<div class="ui">
  <input class="ui-input" style="display: none" type="text" />
</div>

<div class="overlay">
    <div class="tabs-labels"><span class="tabs-label">Commands</span><span class="tabs-label">Info</span><span class="tabs-label">Share</span></div>
      <ul class="tabs-panel commands">
</div>



<div class="container-fluid">
	 ';
}
// 尾部信息
function footer()
{
    echo '
	<p  class="Logo"  style="text-align:center; font-weight:bold; font-size:25px"><img src="img/Logo.png">Copyright (C) 2015 化食创协<br><small>QQ群:244893714</small></p>
 </div>

 <script src="js/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
  <script src="js/title.js"></script>
 <script src="functions.js"></script>
 </body>
 </html>';
}
// 标题栏
function title($title)
{
    echo '
<div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span6">
		   <!--		   <h3 class="text-center">' . $title . '</h3>-->
		   <div id="bgclock" style="float: right"></div>
		</div>
		<div class="span3">
		</div>
	 </div>';
}
// 导航栏
function nav($flag)
{
    $nav_tag = array(0, '登陆/答题', '个人资料', '排名', '帮助说明', '关于我们', 'CHANGELOG');
    $nav_tag_link = array(0, INDEX . '#Index', PROFILE . '#profile', RANKLIST . '#ranklist', HELP . '#help', ABOUT_US, CHANGELOG . '#changelog');
    echo '<div class="row-fluid " >
		 <div class="span2">
		 </div>
	<div class="span8">
			';
    echo '<ul class="nav nav-pills" style="margin-left: 18%">';
    for ($i = 1; $i < count($nav_tag); ++$i) {
        if ($i == $flag) {
            echo '<li class="active"><a href="' . $nav_tag_link[$i] . '">' . $nav_tag[$i] . '</a></li>';
        } else {
            echo '<li><a href="' . $nav_tag_link[$i] . '">' . $nav_tag[$i] . '</a></li>';
        }
    }
    echo '</ul>';
    $user_online_stat = get_online_user();
    echo '

	  <p class="label badge-warning" style="float:right; margin-right: 20%" >登陆:' . $user_online_stat[0] . '人 提交:' . $user_online_stat[1] . '人</p>
   </div>
		<div class="span2">
		</div>
	 </div>';
}
// ranklist
function ranklist()
{
    // $db=connect_db();
    global $db;
    $sqlcmd = 'select name,id,qright,qtime from profile where status=1 order by qright desc,qtime asc';
    $ranklist_num = $db->query($sqlcmd)->num_rows;
    // 排名数量
    if ($ranklist_num == 0) {
        return 0;
    }
    $ranklist_pages = ceil($ranklist_num / RANKLIST_ROWS);
    // 排名页数
    // $ranklist_num 排名行数
    if (!isset($_GET['page']) || $_GET['page'] < 1) {
        $_GET['page'] = 1;
    }
    if ($_GET['page'] > $ranklist_pages) {
        $_GET['page'] = $ranklist_pages;
    }
    if ($_GET['page'] == $ranklist_pages) {
        $ranklist_rows = $ranklist_num - ($_GET['page'] - 1) * RANKLIST_ROWS;
    } else {
        $ranklist_rows = RANKLIST_ROWS;
    }
    $sqlcmd = 'select name,id,qright,qtime from profile where status=1 order by qright desc,qtime asc limit ' . ($_GET['page'] - 1) * RANKLIST_ROWS . ',' . RANKLIST_ROWS;
    $result = $db->query($sqlcmd);
    echo '
	<div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span6">
<script type="text/javascript">
        window.onload = function () {
            document.onkeydown = keyDown;//js获取按键keycode值
      //      $(this).keydown(function (e) {//jquery获取keycode值
        //       alert(e.keyCode.toString());
          //  })
        }
        function keyDown(e) {
            var code=e.which;
	var ans = document.getElementsByName("question");
			if(code==37||code==38||code==87||code==65)
			   url = "?page=' . ($_GET['page'] - 1) . '#ranklist";
			if(code==40||code==39||code==83||code==68||code==32||code==13)
			   url = "?page=' . ($_GET['page'] + 1) . '#ranklist";
window.location.href=url;
        }
    </script>


	  ';
    echo '
	  <a name="ranklist"></a>
	  <table class="table table-bordered  table-condensed table-hover">
	<thead>
		<tr>
		   <th style="text-align: center">排名</th>
			<th style="text-align: center">姓名</th>
			<th style="text-align: center">学号</th>
			<th style="text-align: center">正确率/' . QUESTION_COUNT . '</th>
			<th style="text-align: center">答题时间(秒)</th>
		</tr>
	</thead>
	<tbody>';
    for ($i = 1; $i <= $ranklist_rows; ++$i) {
        $ranklist_i = ($_GET['page'] - 1) * RANKLIST_ROWS + $i;
        $user_rank = $result->fetch_array();
        if (is_login() && $user_rank[1] == $_SESSION['User_id']) {
            echo '<tr class="warning" ><td style="text-align: center;font-weight: bold">' . $ranklist_i . '</td><td style="text-align: center;font-weight: bold">' . $user_rank[0] . '</td><td style="text-align: center;font-weight: bold">' . $user_rank[1] . '</td><td style="text-align: center;font-weight: bold">' . $user_rank[2] . '</td><td style="text-align: center;font-weight: bold">' . $user_rank[3] . '</td></tr>';
        } else if($ranklist_i >=1 && $ranklist_i <=40) {
            echo '<tr class="success" ><td style="text-align: center; color: #0000ff">' . $ranklist_i . '</td><td style="text-align: center; color: #0000ff">' . $user_rank[0] . '</td><td style="text-align: center">' . $user_rank[1] . '</td><td style="text-align: center">' . $user_rank[2] . '</td><td style="text-align: center">' . $user_rank[3] . '</td></tr>';
        }
		else
			 echo '<tr class="success" ><td style="text-align: center">' . $ranklist_i . '</td><td style="text-align: center;">' . $user_rank[0] . '</td><td style="text-align: center">' . $user_rank[1] . '</td><td style="text-align: center">' . $user_rank[2] . '</td><td style="text-align: center">' . $user_rank[3] . '</td></tr>';


    }
    $result->close();
    echo '
 </tbody>
 </table>';
    if ($ranklist_pages > 1) {
        echo '
 <div class="alert alert-info">
				 <button type="button" class="close" data-dismiss="alert">×</button>
				<h4>
					提示!
				 </h4> <strong>方向键或者WSAD键、回车、空格键</strong>可以翻页。
			</div>
 <div class="pagination pagination-left text-center" >
	<ul>
	   <li><button class="btn"><a href="?page=' . ($_GET['page'] - 1) . '#ranklist">上一页</button></a></li>
	   ';
        //			<li><button class="btn">上一页</button></li>
        echo '
 <li><button class="btn"><a href="?page=' . ($_GET['page'] + 1) . '#ranklist">下一页</a></button></li>
</ul>
	  </div>
	  ';
    }
    echo '

		</div>
		<div class="span3">
		</div>
	 </div>
	  ';
}
// CHANGELOG
function changelog()
{
    echo '<div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span6" >
		   <a name="changelog"></a>
		   ';
    echo '
		   <div class="hero-unit well">
			  <h3>' . TITLE . '平台开发日志 <small style="font-style: italic; color: blue">Dev By Netcan(罗能)</small></h3>
			  <small style="color: blue">联系QQ: 1469709759（罗能）</small>

   <div class="page-header" style="margin: 0px" >
</div>
		  <dl>
			 <dt>2015/04/04 14:39</dt>
			 <dd style="text-indent: 2em;">加入最终答题情况...</dd>
			 <dd ><img src="img/201504041439.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>


		   ';
    if (!isset($_GET['more'])) {
        echo '
		  </dl>
		  <p><a class="btn btn-primary btn-large" href="?more=1">查看更多 »</a></p>
</div> ';
    } else {
        echo '
	 <dt>2015/04/04 11:04</dt>
			 <dd style="text-indent: 2em;">加入Logo的canvas特效...</dd>
			 <dd ><img src="img/201504041135.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
<dt>2015/04/03 13:33</dt>
 <dd style="text-indent: 2em;">实现排行榜功能...</dd>
			 <dd ><img src="img/201504031333.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
<dt>2015/04/02 18:25</dt>
 <dd style="text-indent: 2em;">校级百科竞赛已经部署到学校虚拟主机（地址： http://172.18.6.150/ ）。。只能说速度给力。。虚拟机都运行地比我的快，本打算优化数据读取算法，看来没必要了。。Linux出色地展现了服务器性能，。。如果各位测试的话记得不要提交，否则无法继续答题。注意被挤，内测账号有限。还有就是平台支持暂停，下次登陆可以继续答题。</dd>
			 <dd ><img src="img/201504021826.png" class="img-polaroid img-rounded"/ ></dd>
			 <br>
 <dt>2015/04/02 17:54</dt>
			 <dd style="text-indent: 2em;">实现键盘切换题目...</dd>
			 <dd ><img src="img/201504021754.png" class="img-polaroid img-rounded"/ ></dd>
			 <br>
	 <dt>2015/04/02 14:19</dt>
			 <dd style="text-indent: 2em;">实现多处登陆被挤...</dd>
			 <dd ><img src="img/201504021419.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
	 <dt>2015/04/02 09:08</dt>
			 <dd style="text-indent: 2em;">加入答题卡模块...</dd>
			 <dd ><img src="img/201504020908.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
<dt>2015/04/01 22:47</dt>
			 <dd style="text-indent: 2em;">随机题库记录模块完成，剩下的只是很简单的保存数据处理+时间控制了，这次改善了题号按钮，未做过的题目显示为白色，做过的显示为绿色，当前的为蓝色...</dd>
			 <dd ><img src="img/201504012247.gif" class="img-polaroid img-rounded"/ ></dd>
			 <dt>2015/04/01 18:15</dt>
			 <dd style="text-indent: 2em;">随机题库框架实现...（PS: 题目类型仅供测试用...）</dd>
			 <dd ><img src="img/201504011815.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
			 <dt>2015/03/31 18:26</dt>
			 <dd style="text-indent: 2em;">登陆模块实现...</dd>
			 <dd ><img src="img/201503311826.gif" class="img-polaroid img-rounded"/ ></dd>
			 <br>
			 <dt>2015/03/30 23:09</dt>
			 <dd style="text-indent: 2em;">今天下午接管校级百科竞赛平台的开发，汪老师分配了一个远程虚拟机做服务器...</dd>
			 <dd ><img src="img/201503302309.png" class="img-polaroid img-rounded"/ ></dd>



</dl>
</div>

		   ';
    }
    echo '
		  		</div>
		<div class="span3">
		</div>
	 </div>
		   ';
}
// 登陆
function login()
{
    echo '
<div class="row-fluid">
		<div class="span2">
		</div>
		<div class="span8" >
		   <form name="login" class="form-horizontal" style="margin-left: 20%" action="' . LOGIN . '" method="POST">
<div class="control-group"><label class="control-label"  for="ID">学号</label>

   <div class="controls"><input id="ID" name="ID" placeholder="请输入学号..." type="text" /></div>
</div>

<div class="control-group"><label class="control-label" for="Password">密码</label>

   <div class="controls"><input id="Password" name="Password" placeholder="请输入密码...(默认身份证后六位)" type="password" /></div>
</div>

<div class="control-group">
<div class="controls"><button class="btn" type="button" onClick="check_login()">登陆</button></div>
</div>
</form>
		</div>
		<div class="span2">
		</div>
	 </div>

	  ';
}
// 判断登陆状态
function is_login()
{
    if (isset($_SESSION['User_id'])) {
        return true;
    } else {
        return false;
    }
}
// 连接数据库
function connect_db()
{
    $db = new mysqli(MYSQL_HOST, MYSQL_USER, MYSQL_PASSWORD, MYSQL_DB);
    if (mysqli_connect_errno()) {
        echo '<font color=\'red\'>数据库连接失败!</font>';
    } else {
        //   echo "数据库连接成功！<br>";
        $db->query('set names utf8');
    }
    return $db;
}
// 注销
function logout()
{
    if (is_login()) {
        if ($_SESSION['User_qtime_start'] != 0) {
            $_SESSION['User_qtime'] += time() - $_SESSION['User_qtime_start'] + 30;
            if ($_SESSION['User_qtime'] > QUESTION_TIME) {
                $_SESSION['User_qtime'] = QUESTION_TIME;
            }
        }
        update_db(false);
        unset_session();
    }
}
// 销毁session
function unset_session()
{
    session_unset();
    session_destroy();
    unset($_SESSION['User_id']);
    unset($_SESSION['User_name']);
    unset($_SESSION['User_password']);
    unset($_SESSION['User_phone']);
    unset($_SESSION['User_qid']);
    unset($_SESSION['User_qans']);
    unset($_SESSION['User_qmark']);
    unset($_SESSION['User_qright']);
    unset($_SESSION['User_qtime']);
    unset($_SESSION['User_qtime_start']);
    unset($_SESSION['User_status']);
    unset($_SESSION['User_lid']);
}
// 注销模块,$flag表示是否显示关闭按钮
function show_logout($flag)
{
    echo '
	    <div class="row-fluid">
		<div class="span4">
		</div>
		<div class="span4">

		   <a name="profile"></a>
			<div class="alert alert-info"> ';
    if ($flag) {
        echo ' <button type="button" class="close" data-dismiss="alert">×</button> ';
    }
    echo '
				<h4>
					提示!
				 </h4> <strong>登录成功！</strong> 当前登录用户：' . $_SESSION['User_name'] . '
				 <form action="' . LOGOUT . '" method="POST" name="logout">
					<input type="submit" class="btn btn-success" value="注销">
				 </form>
			</div>
		</div>
		<div class="span4">
		</div>
	</div>
	  ';
}
// 更新用户session
function update_session()
{
    if (is_login()) {
        // $db = connect_db();
        global $db;
        $sqlcmd = 'select password,phone,qans,qmark,qright from profile where id =' . $_SESSION['User_id'];
        $result = $db->query($sqlcmd);
        if ($result->num_rows > 0) {
            $profile = $result->fetch_array();
            $_SESSION['User_password'] = $profile[0];
            $_SESSION['User_phone'] = $profile[1];
            $_SESSION['User_qans'] = $profile[2];
            $_SESSION['User_qmark'] = $profile[3] + 1;
            $_SESSION['User_qright'] = $profile[4];
            $result->close();
            $db->close();
        }
    }
}
// 获取题目
function get_question($qid)
{
    if (is_login()) {
        //	 $db = connect_db();
        global $db;
        $sqlcmd = 'select content,A,B,C,D from question where id=' . $_SESSION['User_qid'][$qid - 1];
        $result = $db->query($sqlcmd);
        if ($result->num_rows == 0) {
            header('location:' . INDEX . '?qid=' . $_SESSION['User_qmark'] . '#ques');
            header('location:' . INDEX . '?qid=' . $_SESSION['User_qmark'] . '#ques');
        }
        $question = $result->fetch_array();
        $result->close();
        return $question;
    }
}
// 开始答题
function start_answer()
{
    if (isset($_GET['pause']) && !isset($_GET['qid'])) {
        $_SESSION['User_qtime'] += time() - $_SESSION['User_qtime_start'] + 30;
        $_SESSION['User_qtime_start'] = 0;
        update_db(true);
    }
    if ($_SESSION['User_status'] == USER_SUBMIT || mktime(23, 59, 59, 4, 20, 2099) - time() <=0) {
        echo '
   	<div class="row-fluid">
		<div class="span4">
		</div>
		<div class="span4" >
		   <button class="btn disabled btn-info" type="button" style="margin-left: 40%">答题结束</button>
		   </div>
	<div class="span4">
		</div>
	 </div>
		   ';
    } else {
        if (isset($_GET['qid']) || isset($_GET['submit'])) {
            answer();
            update_db(true);
        } else {
            echo '
 	<div class="row-fluid">
		<div class="span4">
		</div>
		<div class="span4" >';
            echo '
		   <a id="modal-357114" href="#modal-container-357114" role="button" class="btn btn-info"  style="margin-left: 40%" data-toggle="modal">开始/继续答题</a>
			<div id="modal-container-357114" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">
					   注意事项
					</h3>
				</div>
				<div class="modal-body">
				   <p style="text-indent: 2em">
				   请认真完成题目，有问题可反馈给我们。注意比赛时间<strong>'.QUESTION_TIME.'</strong>秒。
					</p>
				   <p style="text-indent: 2em">
				   可采用<font style="font-weight: bold; color: red;">方向键</font>或<font style="font-weight: bold; color: red;">WSAD键</font>或<font style="font-weight: bold; color: red;">回车、空格键</font>来切换题目，
				   <p style="text-indent: 2em">
				   采用<font style="font-weight: bold; color: red;">数字键1, 2, 3, 4</font>来选择A, B, C, D选项



				</div>
				<div class="modal-footer">
				   <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button><a onClick="get_ans(' . $_SESSION['User_qmark'] . ')"><button class="btn btn-primary">开始答题</button></a>
				</div>
			</div>
	   </div>
	<div class="span4">
		</div>
  </div>';
        }
    }
}
// 答题页面
function answer()
{
    if (is_login()) {
        if ($_SESSION['User_status'] == USER_SUBMIT) {
            return;
        }
        if (isset($_GET['submit'])) {
            $_SESSION['User_qright'] = 0;
            $_SESSION['User_status'] = USER_SUBMIT;
            //	 $db = connect_db();
		if($_SESSION['User_qtime'] - QUESTION_TIME > 0)
			$_SESSION['User_qtime'] = QUESTION_TIME;
            global $db;
            foreach ($_SESSION['User_qans'] as $qid => $ans) {
                $sqlcmd = 'select Ans from question where id=' . $_SESSION['User_qid'][$qid];
                $result = $db->query($sqlcmd);
                $question = $result->fetch_array();
                if ($question[0] == $ans) {
                    ++$_SESSION['User_qright'];
                }
            }
            $sqlcmd = 'update profile set qright=' . $_SESSION['User_qright'] . ',qans="' . addslashes(serialize($_SESSION['User_qans'])) . '",qtime=' . $_SESSION['User_qtime'] . ',status=1 where id=' . $_SESSION['User_id'];
            $db->query($sqlcmd);
            $db->close();
            header('location:' . INDEX);
            return;
        }
        if ($_SESSION['User_qtime_start'] != 0) {
            $_SESSION['User_qtime'] += time() - $_SESSION['User_qtime_start'];
        }
        $_SESSION['User_qtime_start'] = time();
        //		 echo "已消耗时间：".$_SESSION['User_qtime']."秒";
        if (is_null($_SESSION['User_qid'])) {
            //	  $db = connect_db();
            global $db;
            $question_nums = $db->query('select id from question')->num_rows;
            srand(time());
            $questions_id = array();
            for ($i = 1; $i <= QUESTION_COUNT; ++$i) {
/*                while ($qid = rand(1, $question_nums)) {
                    if (in_array($qid, $questions_id)) {
                        continue;
                    } else {
                        break;
                    }
                }
				*/
                array_push($questions_id, $i);
            }

            $sqlcmd = 'update profile set qid="' . addslashes(serialize($questions_id)) . '" where id=' . $_SESSION['User_id'];
            $db->query($sqlcmd);
            $db->close();
            $_SESSION['User_qid'] = $questions_id;
        }
        if (is_null($_SESSION['User_qans'])) {
            $_SESSION['User_qans'] = array();
        }
        if (isset($_GET['ans'])) {
            $_SESSION['User_qans'][$_SESSION['User_qmark'] - 1] = $_GET['ans'];
        }
        //	  print_r($_SESSION['User_qans']);
        if (!isset($_GET['qid'])) {
            $_GET['qid'] = $_SESSION['User_qmark'];
        } else {
            if ($_GET['qid'] >= 1 && $_GET['qid'] <= QUESTION_COUNT) {
                $_SESSION['User_qmark'] = $_GET['qid'];
            } else {
                $_GET['qid'] = $_SESSION['User_qmark'];
            }
        }
        $qnext = $_GET['qid'] + 1;
        $qlast = $_GET['qid'] - 1;
        if ($qnext > QUESTION_COUNT) {
            $qnext = QUESTION_COUNT;
        }
        if ($qlast <= 0) {
            $qlast = 1;
        }
        $question = get_question($_GET['qid']);
        echo '
	    <div class="row-fluid">
		<div class="span4">
		</div>
		<div class="span4">';
        echo '
<div class="progress progress-striped active">
   <div class="progress-bar progress-bar-success" role="progressbar"
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
	  style="width: ' . count($_SESSION['User_qans']) * 100 / QUESTION_COUNT . '%;">

<a name="ques" id="ques"></a>
	  <span class="sr-only">' . count($_SESSION['User_qans']) * 100 / QUESTION_COUNT . '% 完成</span>
   </div>

</div>';
        if (QUESTION_TIME - $_SESSION['User_qtime'] <= 0) {
            $_SESSION['User_qtime'] = QUESTION_TIME;
            echo '<script language="JavaScript">
	  alert("时间到！强制提交！");
	  window.location.href = "?submit=1";
   </script>
	  ';
            return;
        }
		/*
        $leave_time = (QUESTION_TIME - $_SESSION['User_qtime']) * 100 / QUESTION_TIME;
        echo '<div class="progress progress-striped active">';
        if ($leave_time >= 70 && $leave_time <= 100) {
            echo '<div class="progress-bar" role="progressbar"
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
	  style="width: ' . $leave_time . '%;">';
        }
        if ($leave_time >= 40 && $leave_time < 70) {
            echo '<div class="progress-bar   progress-bar-info" role="progressbar"
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
	  style="width: ' . $leave_time . '%;">';
        }
        if ($leave_time >= 20 && $leave_time < 40) {
            echo '<div class="progress-bar  progress-bar-warning" role="progressbar "
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
	  style="width: ' . $leave_time . '%;">';
        }
        if ($leave_time >= 0 && $leave_time < 20) {
            echo '<div class="progress-bar  progress-bar-danger" role="progressbar "
      aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
	  style="width: ' . $leave_time . '%;">';
        }
        echo '

	  <span class="sr-only">倒计时: ' . (QUESTION_TIME - $_SESSION['User_qtime']) . '秒</span>
   </div>
</div>
		   '; */
        echo '

<table name="qtable" class="table table-bordered table-hover error">
			  <thead>
				 <tr><th>' . $_GET['qid'] . '</th><th>' . $question[0] . '</th></tr>
		   </thead>
		<tbody>';
        for ($i = 0; $i < 4; ++$i) {
            if (isset($_SESSION['User_qans'][$_SESSION['User_qmark'] - 1]) && $_SESSION['User_qans'][$_SESSION['User_qmark'] - 1] == chr(ord('a') + $i)) {
                echo '<tr class="error"><td><input name="question" type="radio" value="' . chr(ord('a') + $i) . '" checked>' . chr(ord('A') + $i) . '</td><td>' . $question[$i + 1] . '</td></tr>';
            } else {
                echo '<tr class="info"><td><input name="question" type="radio" value="' . chr(ord('a') + $i) . '">' . chr(ord('A') + $i) . '</td><td>' . $question[$i + 1] . '</td></tr>';
            }
        }
        /*	   <tr class="error"><td><input name="question" type="radio" value="a">A</td><td>'.$question[1].'</td></tr>
                		   <tr class="error"><td><input name="question" type="radio" value="b">B</td><td>'.$question[2].'</td></tr>
                		   <tr class="error"><td><input name="question" type="radio" value="c">C</td><td>'.$question[3].'</td></tr>
                		   <tr class="error"><td><input name="question" type="radio" value="d" >D</td><td>'.$question[4].'</td></tr>*/
        echo '
					</tbody>
				 </table>

</div>
</div>
			  ';
        echo '
 <div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span6">

 <script type="text/javascript">
        window.onload = function () {
            document.onkeydown = keyDown;//js获取按键keycode值
      //      $(this).keydown(function (e) {//jquery获取keycode值
        //       alert(e.keyCode.toString());
          //  })
        }

        function keyDown(e) {
            var code=e.which;
	var ans = document.getElementsByName("question");
			if(code==37||code==38||code==87||code==65)
			get_ans(' . $qlast . ');
			if(code==40||code==39||code==83||code==68||code==32||code==13)
			get_ans(' . $qnext . ');
			if(code==49 || code==97)
			ans[0].checked=true;
			if(code==50 || code==98)
			ans[1].checked=true;
			if(code==51 || code==99)
			ans[2].checked=true;
			if(code==52 || code==100)
			ans[3].checked=true;
        }
    </script>





		   <div class="pagination pagination-left text-center" >
			  <a onClick="get_ans(' . $qlast . ')"><button class="btn">上一题</button></a>';
        if ($_SESSION['User_qmark'] < 5) {
            for ($i = 1; $i <= 9; ++$i) {
                if ($i == $_SESSION['User_qmark']) {
                    echo '<a style="color: white;" onClick="get_ans(' . $i . ')" ><button class="btn btn-info" >' . $i . '</button></a>';
                } else {
                    if (array_key_exists($i - 1, $_SESSION['User_qans'])) {
                        echo '<a style="color: white;" onClick="get_ans(' . $i . ')"><button class="btn btn-success">' . $i . '</button></a>';
                    } else {
                        echo '<a onClick="get_ans(' . $i . ')"><button class="btn">' . $i . '</button></a>';
                    }
                }
            }
        }
        if ($_SESSION['User_qmark'] > QUESTION_COUNT - 5) {
            for ($i = QUESTION_COUNT - 8; $i <= QUESTION_COUNT; ++$i) {
                if ($i == $_SESSION['User_qmark']) {
                    echo '<a style="color: white;" onClick="get_ans(' . $i . ')" ><button class="btn btn-info" >' . $i . '</button></a>';
                } else {
                    if (array_key_exists($i - 1, $_SESSION['User_qans'])) {
                        echo '<a style="color: white;" onClick="get_ans(' . $i . ')"><button class="btn btn-success">' . $i . '</button></a>';
                    } else {
                        echo '<a onClick="get_ans(' . $i . ')"><button class="btn">' . $i . '</button></a>';
                    }
                }
            }
        }
        if ($_SESSION['User_qmark'] >= 5 && $_SESSION['User_qmark'] <= QUESTION_COUNT - 5) {
            for ($i = $_SESSION['User_qmark'] - 4; $i <= $_SESSION['User_qmark'] + 4; ++$i) {
                if ($i == $_SESSION['User_qmark']) {
                    echo '<a style="color: white;" onClick="get_ans(' . $i . ')" ><button class="btn btn-info" >' . $i . '</button></a>';
                } else {
                    if (array_key_exists($i - 1, $_SESSION['User_qans'])) {
                        echo '<a style="color: white;" onClick="get_ans(' . $i . ')"><button class="btn btn-success">' . $i . '</button></a>';
                    } else {
                        echo '<a onClick="get_ans(' . $i . ')"><button class="btn">' . $i . '</button></a>';
                    }
                }
            }
        }
        /*<li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">4</a></li>
          <li><a href="#">5</a></li>
          <li><a href="#" class="btn btn-success">6</a></li>
          <li><a href="#">7</a></li>
          <li><a href="#">8</a></li>
          <li><a href="#">9</a></li>*/
        echo '
		<a onClick="get_ans(' . $qnext . ')"><button class="btn">下一题</button></a>
		</div>
';
        echo '
<a href="?pause=1"><button class="btn btn-danger" style="margin-left: 15%; ">暂停答题</button></a>
<div style="margin-left: 78%">
	  <a id="modal-322094" href="#modal-container-322094" role="button" class="btn btn-warning " data-toggle="modal">答题卡</a>

<br>
<br>
<a href="?submit=1"><button class="btn btn-danger" style="margin-left: 0%; ">全部提交</button></a>
   <div id="modal-container-322094" class="modal hide fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-header">
					 <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">
					   确认提交？
					</h3>
				</div>

				<div class="modal-body">
				   <p style="text-indent: 2em">

				   你当前完成了' . count($_SESSION['User_qans']) . '道题,还剩下' . (QUESTION_COUNT - count($_SESSION['User_qans'])) . '道未完成。
					</p>
				   <p style="text-indent: 2em">
				   答题卡：
				   </p>
<table class="table table-hover table-condensed " >
<tbody>
';
        for ($j = 0; $j < ceil(QUESTION_COUNT / 10); ++$j) {
            echo '<tr>';
            for ($i = 1; $i <= 10; ++$i) {
                //		 echo '<td style="text-align:center">'.($j*10+$i).'</td>';
                if (array_key_exists($j * 10 + $i - 1, $_SESSION['User_qans'])) {
                    echo '<td style="text-align:center"><a onClick="get_ans(' . ($j * 10 + $i) . ')"><button class="btn btn-success" style="width： 20px">' . ($j * 10 + $i) . '</button></a></td>';
                } else {
                    echo '<td style="text-align:center"><a onClick="get_ans(' . ($j * 10 + $i) . ')"><button class="btn" style="width： 20px">' . ($j * 10 + $i) . '</button></a></td>';
                }
            }
            echo '</tr>';
        }
        /*   <tr>
             <td>1</td><td>2</td>
             <tr> */
        echo '
</tbody>
</table>
				</div>
				<div class="alert alert-info"  style="margin-left:5%;width: 80%" >
   <button type="button" class="close" data-dismiss="alert">×</button>
				<h4>
					提示!
				 </h4> <strong>注意!</strong> 只有一次提交机会！请慎重提交！
			</div>
				<div class="modal-footer">
				   <button class="btn" data-dismiss="modal" aria-hidden="true">关闭</button><a href="?submit=1"><button class="btn btn-primary">提交</button></a>
				</div>
			</div>

';
        echo '
	 </div>
	  ';
    }
}
// 个人信息页面
function profile()
{
    if (!is_login()) {
        echo '
	    <div class="row-fluid">
		<div class="span4">
		</div>
		<div class="span4">
			<div class="alert alert-error">
				<h4>
					警告!
				 </h4> <strong>提示</strong> 尚未登陆！
			</div>
		</div>
		<div class="span4">
		</div>
	</div>
	  ';
    } else {
        show_logout(false);
        echo '

	    <div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span8">
		   <form style="margin-left: 6%" class="form-horizontal" name="profile" action="' . UPDATE . '" method="POST">
			  <div class="control-group"><label class="control-label"  for="id">学号</label>
				 <div class="controls"><input name="id" value="' . $_SESSION['User_id'] . '" type="text"  readonly /></div>
		   <br>
		<label class="control-label"  for="name">姓名</label>
		   <div class="controls"><input name="name" value="' . $_SESSION['User_name'] . '" type="text"  readonly /></div>
		   <br>
		   <label class="control-label"  for="phone">手机号</label>
			  <div class="controls"><input name="phone" placeholder="请填写手机号码有利于我们联系" value="' . $_SESSION['User_phone'] . '" type="text" maxLength=11 /><h3 style="display: inline;  font-weight: bold;color:red; ">&nbsp*</h3></div>
		   <br>
<label class="control-label"  for="qq">QQ</label>
			  <div class="controls"><input name="qq" placeholder="QQ..." value="' . $_SESSION['User_qq'] . '" type="text" maxLength=11 /><h3 style="display: inline;  font-weight: bold;color:red; ">&nbsp*</h3></div>
		   <br>
		   <label class="control-label"  for="password">密码</label>
			  <div class="controls"><input value="' . $_SESSION['User_password'] . '" type="text"   name="password" maxLength=12/><h3 style="display: inline;  font-weight: bold;color:red; ">&nbsp*</h3></div>
		   <br>
		   <div class="controls">  <input type="button" class="btn btn-info" value="更新" name="update" onClick="update_check()"></div>
		</div>
		</form>


</div>
		<div class="span1">
		</div>
	 </div>
	 <script language="JavaScript" >
function update_check() {
	if(profile.password.value.length <6){
		alert("密码长度至少6位！");
		return false;
	}

	if(profile.phone.value != "")
	if(profile.phone.value.length != 11 || isNaN(profile.phone.value))
	{
	   alert("手机号码有误！");
		return false;
	}
	if(profile.qq.value != "")
	if(isNaN(profile.qq.value))
	{
	   alert("QQ号码有误！");
		return false;
	}

	if(profile.password.value == "' . $_SESSION['User_password'] . '" && profile.phone.value == "' . $_SESSION['User_phone'] . '" && profile.qq.value == "' . $_SESSION['User_qq'] . '" ) {
	   alert("无需更新信息!");
		return false;
	}
	profile.submit();
}
</script>

	  ';
    }
}
// 竞赛帮助信息
function help()
{
    echo '
	    <div class="row-fluid">
		<div class="span3">
		</div>
		<div class="span6">
		   <a name="help"></a>
		   <div class="hero-unit well">
			  <h2>操作说明</h2>
			  ';
    echo CONTEST_DETAIL;
    echo '
		<div>
		</div>
		<div class="span3">
		</div>
	 </div>
';
}
// 更新数据库,$flag为真则在线状态，否则离线。
function update_db($flag)
{
    if (is_login()) {
        //   $db = connect_db();
        global $db;
        if ($flag) {
            $sqlcmd = 'update profile set online=' . USER_ONLINE . ',status=' . $_SESSION['User_status'] . ',qmark=' . ($_SESSION['User_qmark'] - 1) . ',qtime=' . $_SESSION['User_qtime'] . ',qans="' . addslashes(serialize($_SESSION['User_qans'])) . '",qid="' . addslashes(serialize($_SESSION['User_qid'])) . '" where id=' . $_SESSION['User_id'];
        } else {
            $sqlcmd = 'update profile set online=' . USER_OFFLINE . ',status=' . $_SESSION['User_status'] . ',qmark=' . ($_SESSION['User_qmark'] - 1) . ',qtime=' . $_SESSION['User_qtime'] . ',qans="' . addslashes(serialize($_SESSION['User_qans'])) . '",qid="' . addslashes(serialize($_SESSION['User_qid'])) . '" where id=' . $_SESSION['User_id'];
        }
        $db->query($sqlcmd);
    }
}
// 显示最终答题卡
function show_ans()
{
    $open_ans = false;
    if (mktime(23, 59, 59, 4, 12, 2015) - time() >= 0 && is_login() && $_SESSION['User_status'] == USER_SUBMIT) {
	   echo '<p style="text-align:center; font-weight:bold">比赛结束后可查看答题情况！</p>';
    } else {
        $open_ans = true;
    }
    if (is_login() && $_SESSION['User_status'] == USER_SUBMIT && $open_ans) {
        global $db;
        if (isset($_GET['pqid']) && $_GET['pqid'] >= 1 && $_GET['pqid'] <= QUESTION_COUNT) {
            $question = get_question($_GET['pqid']);
            echo '<div class="row-fluid">
			   <div class="span3">
			   </div>

			   <div class="span6">

				  ';
            echo '
		   <a name="anssheet"></a>
		   <table name="qtable" class="table table-bordered table-hover error">
			  <thead>
				 <tr><th>' . $_GET['pqid'] . '</th><th>' . $question[0] . '</th></tr>
		   </thead>
		<tbody>';
            for ($i = 0; $i < 4; ++$i) {
                if (isset($_SESSION['User_qans'][$_GET['pqid'] - 1]) && $_SESSION['User_qans'][$_GET['pqid'] - 1] == chr(ord('a') + $i)) {
                    echo '<tr class="error"><td><input name="question" type="radio" value="' . chr(ord('a') + $i) . '" checked>' . chr(ord('A') + $i) . '</td><td>' . $question[$i + 1] . '</td></tr>';
                } else {
                    echo '<tr class="info"><td><input name="question" type="radio" value="' . chr(ord('a') + $i) . '">' . chr(ord('A') + $i) . '</td><td>' . $question[$i + 1] . '</td></tr>';
                }
            }
            echo '
					</tbody>
				 </table>';
            $sqlcmd = 'select Ans from question where id=' . $_SESSION['User_qid'][$_GET['pqid'] - 1];
            $ans = $db->query($sqlcmd)->fetch_array();
            echo '你的答案：<strong>' . strtoupper($_SESSION['User_qans'][$_GET['pqid'] - 1]) . '</strong> 正确答案：<strong>' . strtoupper($ans[0]) . '</strong>';
            echo '
			  </div>
<div class="span3">
</div>
</div>

';
        }
        echo '	    <div class="row-fluid">

		<div class="span3">
		</div>
		<div class="span6">
	  <p style="text-align:center; font-weight:bold">最终答题情况，点<strong>题号</strong>可以查看答案。</p>

		 <table class="table table-hover table-condensed" >
		 <tbody>';
        for ($j = 0; $j < ceil(QUESTION_COUNT / 10); ++$j) {
            echo '<tr>';
            for ($i = 1; $i <= 10; ++$i) {
                if (array_key_exists($j * 10 + $i - 1, $_SESSION['User_qans'])) {
                    $sqlcmd = 'select Ans from question where id=' . $_SESSION['User_qid'][$j * 10 + $i - 1];
                    $ans = $db->query($sqlcmd)->fetch_array();
                    if ($ans[0] == $_SESSION['User_qans'][$j * 10 + $i - 1]) {
                        echo '
		 <td style="text-align:center"><a class="btn btn-success" style="width： 20px" href="?pqid=' . ($j * 10 + $i) . '#anssheet">' . ($j * 10 + $i) . '</a></td>';
                    } else {
                        echo '
		 <td style="text-align:center"><a class="btn btn-danger" style="width： 20px" href="?pqid=' . ($j * 10 + $i) . '#anssheet">' . ($j * 10 + $i) . '</a></td>';
                    }
                } else {
                    echo '
		 <td style="text-align:center"><a class="btn" style="width： 20px"  href="?pqid=' . ($j * 10 + $i) . '#anssheet" >' . ($j * 10 + $i) . '</a></td>
		 ';
                }
            }
            echo '</tr>';
        }
        echo '
		 </tbody>
	  </table>
   </div>
	<div class="span3">
	</div>
 </div>
			';
    } elseif (is_login() && $_SESSION['User_status'] != USER_SUBMIT) {
	   echo '<p style="text-align:center; font-weight:bold">尚未提交无法查看答题情况！</p>';
    }
}
