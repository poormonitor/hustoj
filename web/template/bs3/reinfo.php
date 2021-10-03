<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="icon" href="../../favicon.ico">

  <title><?php echo $OJ_NAME ?></title>
  <?php include("template/$OJ_TEMPLATE/css.php"); ?>


  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
<script src="https://cdn.bootcss.com/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>

  <div class="container">
    <?php include("template/$OJ_TEMPLATE/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">

      <pre id='code' class="alert alert-error"><?php echo $view_reinfo ?></pre>
      <div id='errexp'>
        <!--Explain:-->
      </div>

    </div>

  </div> <!-- /container -->

  <!-- Bootstrap core JavaScript
  ================================================== -->
  <!-- Placed at the end of the document so the pages load faster -->
  <?php include("template/$OJ_TEMPLATE/js.php"); ?>
  <script>
    var pats = new Array();
    var exps = new Array();
    pats[0] = /A Not allowed system call.* /;
    exps[0] = "<?php echo $MSG_A_NOT_ALLOWED_SYSTEM_CALL ?>";
    pats[1] = /Segmentation fault/;
    exps[1] = "<?php echo $MSG_SEGMETATION_FAULT ?>";
    pats[2] = /Floating point exception/;
    exps[2] = "<?php echo $MSG_FLOATING_POINT_EXCEPTION ?>";
    pats[3] = /buffer overflow detected/;
    exps[3] = "<?php echo $MSG_BUFFER_OVERFLOW_DETECTED ?>";
    pats[4] = /Killed/;
    exps[4] = "<?php echo $MSG_PROCESS_KILLED ?>";
    pats[5] = /Alarm clock/;
    exps[5] = "<?php echo $MSG_ALARM_CLOCK ?>";
    pats[6] = /CALLID:20/;
    exps[6] = "<?php echo $MSG_CALLID_20 ?>";
    pats[7] = /ArrayIndexOutOfBoundsException/;
    exps[7] = "<?php echo $MSG_ARRAY_INDEX_OUT_OF_BOUNDS_EXCEPTION ?>";
    pats[8] = /StringIndexOutOfBoundsException/;
    exps[8] = "<?php echo $MSG_STRING_INDEX_OUT_OF_BOUNDS_EXCEPTION ?>";

    explain();

    <?php if (isset($OJ_DOWNLOAD) && $OJ_DOWNLOAD) echo  "showDownload();" ?>
  </script>
</body>

</html>