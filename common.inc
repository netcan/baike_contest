<?php
// 调用nav函数的$flag参数由下顺序依次为1-7
define('LOGIN',"login.php");
define('ANSWER',"answer.php");
define('PROFILE',"profile.php");
define('RANKLIST',"ranklist.php");
define('ADMIN',"admin.php"); 
define('HELP',"help.php");
define('ABOUT_US',"about_us.php"); 
define('CHANGELOG',"changelog.php"); 

define('INDEX',"index.php");
define('LOGOUT',"logout.php");
define('UPDATE',"update.php");

// 用户状态
define('USER_UNSUBMIT', 0);
define('USER_SUBMIT', 1);
define('USER_OFFLINE', 0);
define('USER_ONLINE', 1);

// 网站标题
define('TITLE',"百科知识竞赛");

// 数据库变量
define('MYSQL_HOST','localhost');
define('MYSQL_USER','root');
define('MYSQL_PASSWORD','Allanmysql123');
define('MYSQL_DB','baike_contest');

// 抽题数量
define('QUESTION_COUNT', 50);
// 做题时间
define('QUESTION_TIME', 720);

// 排名行数
define('RANKLIST_ROWS', 15);

// 竞赛说明
define('CONTEST_DETAIL', '

   <p style="text-indent: 2em">你想拓展自己的课外知识吗？你想锻炼自己的答题能力吗？你想看到同学们在场上奋力拼搏的飒爽英姿吗？化工与食品加工系创新创业协会第三届百科知识竞赛诚挚邀请您参与！</p>
<p style="text-indent: 2em">此次活动的知识面囊括政治、历史、地理、文化等诸多方面，旨在促进学生们之间的文化交流，激发工大学子追求知识的热情。</p>
<ul class=" list-paddingleft-2" style="list-style-type: disc;">
    <li>
        <p>
            注意平台<span style="color: rgb(255, 0, 0);"><strong>开放时间</strong></span>！
        </p>
    </li>
    <li>
        <p>
            各位同学请用自己的<strong><span style="color: rgb(255, 0, 0);">学号+身份证</span></strong>后六位登陆，登陆后请到<span style="color: rgb(255, 0, 0);"><strong>个人资料</strong></span>中<span style="color: rgb(255, 0, 0);"><strong>完善信息</strong></span>！无法登陆请联系我！
        </p>
    </li>
 <li>
        <p>
            平台答题时间有限，<span style="color: rgb(255, 0, 0);"><strong>12分钟(720秒)</strong><span style="color: rgb(0, 0, 0);">，请注意答题时间，超时<span style="color: rgb(255, 0, 0);"><strong>强制提交</strong></span>！</span></span>
        </p>
    </li>
    <li>
        <p>
            <span style="color: rgb(255, 0, 0);"><span style="color: rgb(0, 0, 0);">若电脑答题，可采用<span style="color: rgb(255, 0, 0);"><strong>方向键</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>WSAD</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>空格</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>回车</strong></span>切题，数字键<span style="color: rgb(255, 0, 0);"><strong>1</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>2</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>3</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>4</strong></span>选择选项<span style="color: rgb(255, 0, 0);"><strong>A</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>B</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>C</strong></span>、<span style="color: rgb(255, 0, 0);"><strong>D</strong></span>。</span></span>
        </p>
    </li>
   <li>
        <p>
            答题过程中可<span style="color: rgb(255, 0, 0);"><strong>暂停</strong></span>，或者<span style="color: rgb(255, 0, 0);"><strong>注销</strong></span>，这两种操作将罚时(<span style="color: rgb(0, 0, 255);"><strong>罚30秒</strong></span>),下次可<strong><span style="color: rgb(255, 0, 0);">继续</span></strong>答题！尽量<span style="color: rgb(255, 0, 0);"><strong>一次性提交<span style="color: rgb(0, 0, 0);"></span></strong><span style="color: rgb(0, 0, 0);">(在答题页面点击<span style="color: rgb(0, 0, 255);"><strong>答题卡</strong></span>-&gt;<span style="color: rgb(0, 0, 255);"><strong>提交</strong></span>)</span></span>
        </p>
    </li>
   <li>
        <p>
            比赛<span style="color: rgb(255, 0, 0);"><strong>结束</strong></span>后开放<span style="color: rgb(255, 0, 0);"><strong>个人答题卡</strong></span>，可查看自己<span style="color: rgb(255, 0, 0);"><strong>做题情况</strong></span>！
        </p>
</ul>


');

?>
