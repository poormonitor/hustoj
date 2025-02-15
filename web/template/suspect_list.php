<!DOCTYPE html>
<html lang="<?php echo $OJ_LANG ?>">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="<?php echo $OJ_NAME ?>">
  <link rel="shortcut icon" href="/favicon.ico">
  <style>
    .table-td-space td {
      padding: 0 2px;
    }
  </style>

  <title>
    <?php echo $MSG_SUSPECT . " - " . $OJ_NAME ?>
  </title>

  <?php
  include("template/css.php");
  include("./include/iplocation.php");
  ?>

</head>

<body>
  <div class="container">
    <?php include("template/nav.php"); ?>
    <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
      <center>
        <div>
          <h3><?php echo $MSG_CONTEST_ID ?> : <?php echo $view_cid ?> - <?php echo $view_title ?></h3>
          <p>
            <?php echo $view_description ?>
          </p>
          <br>
          <?php echo $MSG_SERVER_TIME ?> : <span id=nowdate> <?php echo date("Y-m-d H:i:s") ?></span>
          <br>

          <?php if (isset($OJ_RANK_LOCK_PERCENT) && $OJ_RANK_LOCK_PERCENT != 0) { ?>
            Lock Board Time: <?php echo date("Y-m-d H:i:s", $view_lock_time) ?><br />
          <?php } ?>

          <?php if ($now > $end_time) {
            echo "<span class=text-muted>$MSG_Ended</span>";
          } else if ($now < $start_time) {
            echo "<span class=text-success>$MSG_Start&nbsp;</span>";
            echo "<span class=text-success>$MSG_TotalTime</span>" . " " . formatTimeLength($end_time - $start_time);
          } else {
            echo "<span class=text-danger>$MSG_Running</span>&nbsp;";
            echo "<span class='text-danger'>$MSG_LeftTime</span> <span class='time-left'>" . formatTimeLength($end_time - $now) . "</span>";
          }
          ?>

          <br><br>

          <?php echo $MSG_CONTEST_STATUS ?> :

          <?php
          if ($now > $end_time)
            echo "<span class=text-muted>" . $MSG_End . "</span>";
          else if ($now < $start_time)
            echo "<span class=text-success>" . $MSG_Start . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Running . "</span>";
          ?>
          &nbsp;&nbsp;

          <?php echo $MSG_CONTEST_OPEN ?> :

          <?php if ($view_private == '0')
            echo "<span class=text-primary>" . $MSG_Public . "</span>";
          else
            echo "<span class=text-danger>" . $MSG_Private . "</span>";
          ?>

          <br>

          <?php echo $MSG_START_TIME ?> : <?php echo $view_start_time ?>
          <br>
          <?php echo $MSG_END_TIME ?> : <?php echo $view_end_time ?>
          <br><br>

          <div class="btn-group">
            <a href="contest.php?cid=<?php echo $cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_PROBLEMS ?></a>
            <a href="status.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_SUBMIT ?></a>
            <a href="contestrank.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STANDING ?></a>
            <a href="contestrank-oi.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo "OI" . $MSG_STANDING ?></a>
            <a href="conteststatistics.php?cid=<?php echo $view_cid ?>" class="btn btn-primary btn-sm"><?php echo $MSG_STATISTICS ?></a>
            <a href="suspect_list.php?cid=<?php echo $view_cid ?>" class="btn btn-warning btn-sm"><?php echo $MSG_IP_VERIFICATION ?></a>
            <?php if (isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'contest_creator'])) { ?>
              <a href="user_set_ip.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_SET_LOGIN_IP ?></a>
              <a target="_blank" href="admin/contest_edit.php?cid=<?php echo $view_cid ?>" class="btn btn-success btn-sm"><?php echo $MSG_EDIT ?></a>
            <?php } ?>
          </div>
        </div>
      </center>

      <br>

      <div>
        <center>
          <?php echo $MSG_CONTEST_SUSPECT1 ?><br><br>
          <div class='table-responsive'>
            <table class="table-hover table-striped text-center table-td-space" align=center width=90% border=0>
              <tr class='text-nowrap'>
                <th class="text-center">IP address</th>
                <th class="text-center">IP info</th>
                <th class="text-center">Used ID</th>
                <th class="text-center">Trace</th>
                <th class="text-center">Time</th>
                <th class="text-center">IP address count</th>
              </tr>

              <?php
              foreach ($result1 as $row) {
                echo "<tr class='text-nowrap'>";
                $ipaddr = $row["ip"];
                $ipinfo = getLocationFull($ipaddr);
                echo "<td>" . $ipaddr . "</td>";
                echo "<td>" . $ipinfo . "</td>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>";
                echo "<a href='../userinfo.php?user=" . $row['user_id'] . "'><sub>" . $MSG_USERINFO . "</sub></a> <sub>/</sub> ";
                echo "<a href='../status.php?cid=$contest_id&user_id=" . $row['user_id'] . "'><sub>" . $MSG_CONTEST . " " . $MSG_SUBMIT . "</sub></a>";
                echo "</td>";
                echo "<td>" . $row['in_date'];
                echo "<td>" . $row['c'] . "</td>";
                echo "</tr>";
              }
              ?>

            </table>
          </div>
        </center>
      </div>

      <br><br>

      <div>
        <center>
          <?php echo $MSG_CONTEST_SUSPECT2 ?><br><br>
          <div class='table-responsive'>
            <table class="table-hover table-striped text-center table-td-space" align=center width=90% border=0>
              <tr class='text-nowrap'>
                <th class="text-center">Used ID</th>
                <th class="text-center">Trace</th>
                <th class="text-center">Used IP address</th>
                <th class="text-center">IP info</th>
                <th class="text-center">Time</th>
                <th class="text-center">IP address count</th>
              </tr>

              <?php
              foreach ($result2 as $row) {
                echo "<tr class='text-nowrap'>";
                echo "<td>" . $row['user_id'] . "</td>";
                echo "<td>";
                echo "<a href='../userinfo.php?user=" . $row['user_id'] . "'><sub>" . $MSG_USERINFO . "</sub></a> <sub>/</sub> ";
                echo "<a href='../status.php?cid=$contest_id&user_id=" . $row['user_id'] . "'><sub>" . $MSG_CONTEST . " " . $MSG_SUBMIT . "</sub></a>";
                echo "</td>";
                $ipaddr = $row["ip"];
                $ipinfo = getLocationFull($ipaddr);
                echo "<td>" . $ipaddr . "</td>";
                echo "<td>" . $ipinfo . "</td>";
                echo "<td>" . $row['time'] . "</td>";
                echo "<td>" . $row['c'] . "</td>";
                echo "</tr>";
              }
              ?>
            </table>
          </div>
        </center>
      </div>

    </div>

  </div>

  <?php include("template/js.php"); ?>

  <script src="<?php echo $OJ_CDN_URL ?>include/sortTable.min.js"></script>

  <script>
    var diff = new Number("<?php echo round(microtime(true) * 1000) ?>") - new Date().getTime();
    //swal(diff);
    function clock() {
      var x, h, m, s, n, xingqi, y, mon, d;
      var x = new Date(new Date().getTime() + diff);
      y = x.getYear() + 1900;

      if (y > 3000)
        y -= 1900;

      mon = x.getMonth() + 1;
      d = x.getDate();
      xingqi = x.getDay();
      h = x.getHours();
      m = x.getMinutes();
      s = x.getSeconds();
      n = y + "-" + (mon >= 10 ? mon : "0" + mon) + "-" + (d >= 10 ? d : "0" + d) + " " + (h >= 10 ? h : "0" + h) + ":" + (m >= 10 ? m : "0" + m) + ":" + (s >= 10 ? s : "0" + s);

      //swal(n);
      document.getElementById('nowdate').innerHTML = n;
      setTimeout("clock()", 1000);
    }
    setTimeout("clock()", diff > 0 ? diff % 1000 : 1000 + diff % 1000);
  </script>

</body>

</html>