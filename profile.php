<?php
   include("functions.php");
   head("个人资料");
   title(TITLE);
   nav(2);
   profile();
   if(is_login())
   show_ans();
?>

<?php
footer();
?>
