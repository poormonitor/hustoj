<?php
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'password_setter'])
)) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
}

if (isset($_POST["do"])) {
  require_once("../include/check_post_key.php");
}
?>
<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <?php include("../template/css.php"); ?>
  <title><?php echo $OJ_NAME ?></title>
</head>

<body>
  <div class='container'>
    <?php include("../template/nav.php") ?>
    <div class='jumbotron'>
      <div class='row lg-container'>
        <?php require_once("sidebar.php") ?>
        <div class='col-md-9 col-lg-10 p-0'>
          <center>
            <h3><?php echo $MSG_SCHOOL . "-" . $MSG_EDIT ?></h3>
          </center>

          <div class='container'>

            <?php
            if (isset($_POST['do']) and isset($_POST['prefix']) and $_POST['prefix'] != "" and isset($_POST['unit']) and $_POST['unit'] != "") {
              //echo $_POST['user_id'];
              //echo $_POST['passwd'];
              require_once("../include/my_func.inc.php");

              $reg = $_POST['prefix'] . ".{5}";
              $pieces = pdo_query("SELECT * FROM users WHERE user_id REGEXP ?", $reg);
              $count = 0;
              foreach ($pieces as $i) {
                $user = $i["user_id"];
                $class = trim($_POST['unit']) . "（" . strval(intval(substr($user, 3, 2))) . "）班";
                pdo_query("UPDATE `users` SET `school`=? WHERE `user_id`=?", $class, $user);
                $count += 1;
              }
              echo "<div class='alert alert-success' role='alert' style='margin-left: 10px;margin-right: 10px;margin-top: 10px;'>Successfully set $count user(s).</div>";
            }
            ?>

            <br>
            <form action="user_unit_edit.php" method="post" class="form-horizontal">
              <div class="form-group">
                <?php require_once("../include/set_post_key.php"); ?>
                <div class='col-md-4'></div>
                <div class='col-md-4'>
                  <label><?php echo $MSG_PREFIX ?></label>
                  <input type="text" name=prefix class="form-control search-query" placeholder="20" style='margin-top:5px;margin-bottom:10px'>
                  <label><?php echo $MSG_SCHOOL ?></label>
                  <input type="text" name=unit class="form-control search-query" placeholder="高二" style='margin-top:5px;margin-bottom:10px'>
                  <input type=hidden name=getkey value="<?php echo end($_SESSION[$OJ_NAME . '_' . 'getkey']) ?>">
                  <button name="do" type="hidden" value="do" class="btn btn-default btn-block" style='margin-top:10px;'><?php echo $MSG_SAVE ?></button>
                </div>
                <div class='col-md-4'></div>
              </div>

            </form>

          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>