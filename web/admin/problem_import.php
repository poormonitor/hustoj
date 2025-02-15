<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
  || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
)) {
  $view_swal_params = "{title:'$MSG_PRIVILEGE_WARNING',icon:'error'}";
  $error_location = "../index.php";
  require("../template/error.php");
  exit(0);
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
          <?php

          function writable($path)
          {
            $path = realpath($path);
            if (!is_dir($path)) {
              $path = dirname($path);
            }

            $temp_file = $path . '/.testifwritable.txt';
            $file = @fopen($temp_file, 'w');
            if ($file === false) {
              return false;
            }

            fclose($file);
            @chmod($temp_file, 0777);
            @unlink($temp_file);

            return true;
          }

          $maxfile = min(ini_get("upload_max_filesize"), ini_get("post_max_size"));

          echo "<center><h3>" . $MSG_PROBLEM . "-" . $MSG_IMPORT . "</h3></center>";

          ?>

          <div class="container">
            <br><br>
            <?php
            $show_form = true;

            if (!writable($OJ_DATA)) {
              echo "- You need to add  $OJ_DATA into your open_basedir setting of php.ini,<br>
                    or you need to execute:<br>
                    <b>chmod 775 -R $OJ_DATA && chgrp -R www-data $OJ_DATA</b><br>
                    you can't use import function at this time.<br>";

              if ($OJ_LANG == "cn")
                echo "权限异常，请先去执行sudo chmod 775 -R $OJ_DATA <br> 和 sudo chgrp -R www-data $OJ_DATA <br>";

              $show_form = false;
            }

            if (!file_exists("../upload"))
              mkdir("../upload");

            if (!writable("../upload")) {
              echo "../upload is not writable, <b>chmod 770</b> to it.<br>";
              $show_form = false;
            }
            ?>

            <?php if ($show_form) { ?>
              - Import Problem XML<br><br>
              <form class='form-inline' action='problem_import_xml.php' method=post enctype="multipart/form-data">
                <div class='form-group'>
                  <input type=file name=fps>
                </div>
                <br><br>
                <br><br><br>
                <center>
                  <div class='form-group'>
                    <button class='btn btn-primary btn-sm' type=submit>Upload to EOJ</button>
                  </div>
                </center>
                <?php require_once("../include/set_post_key.php"); ?>
              </form>
            <?php } ?>

            <br><br>

            - Import FPS data, please make sure you file is smaller than [<?php echo $maxfile ?>] or set upload_max_filesize and post_max_size in <span style='color:blue'>php.ini</span><br>
            - If you fail on import big files[10M+],try enlarge your [memory_limit] setting in <span style='color:blue'>php.ini</span><br>
            - To find the php configuration file, use <span style='color:blue'> find /etc -name php.ini </span>

          </div>
          <br>
        </div>
      </div>
    </div>
  </div>
  <?php require_once("../template/js.php"); ?>
</body>

</html>