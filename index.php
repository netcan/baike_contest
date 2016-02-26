<?php
   include("functions.php");
   head("登陆/答题");
   title(TITLE);
   nav(1);
?>

<a name="Index"></a>
<?php 
   if(mktime(23,59,59,4,10,2015)-time()<=0) {

   if(!is_login())
   login();
   else  {
	  show_logout(true);
start_answer();

}
}
else  {
echo '
<script language="JavaScript">
function AddFavorite(sURL, sTitle) {

    sURL = encodeURI(sURL);

    try {

        window.external.addFavorite(sURL, sTitle);

    } catch (e) {

    try {

        window.sidebar.addPanel(sTitle, sURL, "");

    } catch (e) {

        alert("加入收藏失败,请使用Ctrl+D进行添加,或手动在浏览器里进行设置.");

        }

    }

}
</script>
';
echo '<h1 style="text-align: center;">平台开放时间：<font color="blue">2015/04/11 00:00:00</font><br>请到<font color="blue">帮助说明</font>查看平台操作方法!<br>点击下面会徽有<font color="blue">惊喜</font>哦~<br>请将本页<font color="blue"><a onClick="AddFavorite(\'http://172.18.6.150\',\'百科竞赛平台\')" href="javascript:void(0)" title="加入收藏">收藏</a></font>！</h1>';
}

echo '<h1 style="text-align: center">初赛结束！<br>请前40名的同学登陆到个人资料中完善信息!</h1>';
?>
<?php
footer();
?>
