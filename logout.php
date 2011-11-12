<?php
setcookie("user_id","",time()-3600);
setcookie("user_name","",time()-3600);
setcookie("admin_access","",time()-3600);
setcookie("add_access","",time()-3600);
setcookie("edit_access","",time()-3600);

header("location: /");
?>