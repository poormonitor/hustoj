<?php
$OJ_CACHE_SHARE = false;
$cache_time = 10;
require_once('./include/cache_start.php');
require_once('./include/db_info.inc.php');
require_once('./include/setlang.php');
$view_title = $MSG_CONTEST . $MSG_RANKLIST;
$title = "";
require_once("./include/const.inc.php");
require_once("./include/my_func.inc.php");
if (!isset($_SESSION[$OJ_NAME . '_' . 'user_id'])) {
  header("location:loginpage.php");
  exit(0);
}
class TM
{
  var $solved = 0;
  var $time = 0;
  var $p_wa_num = array(0);
  var $p_ac_sec = array(0);
  var $p_pass_rate = array(0);
  var $user_id;
  var $nick;
  var $total = 0;
  function __constuct()
  {
    $this->solved = 0;
    $this->time = 0;
    $this->p_wa_num = array(0);
    $this->p_ac_sec = array(0);
    $this->p_pass_rate = array(0);
    $this->total = 0;
  }
  function Add($pid, $sec, $res)
  {
    if ($pid === NULL) {
      return;
    }
    //              echo "Add $pid $sec $res<br>";
    if (isset($this->p_ac_sec[$pid]) && $this->p_ac_sec[$pid] > 0)
      return;
    if ($res * 100 < 99) {
      if (isset($this->p_pass_rate[$pid])) {
        if ($res > $this->p_pass_rate[$pid]) {
          $this->total -= $this->p_pass_rate[$pid] * 100;
          $this->p_pass_rate[$pid] = $res;
          $this->total += $this->p_pass_rate[$pid] * 100;
        }
      } else {
        $this->p_pass_rate[$pid] = $res;
        $this->total += $res * 100;
      }
      if (isset($this->p_wa_num[$pid])) {
        $this->p_wa_num[$pid]++;
      } else {
        $this->p_wa_num[$pid] = 1;
      }
    } else {
      $this->p_ac_sec[$pid] = $sec;
      $this->solved++;
      if (!isset($this->p_wa_num[$pid])) $this->p_wa_num[$pid] = 0;
      if (isset($this->p_pass_rate[$pid])) {
        $this->total -= $this->p_pass_rate[$pid] * 100;
      } else {
        $this->p_pass_rate[$pid] = $res;
      }

      $this->total += 100;
      $this->time += $sec + $this->p_wa_num[$pid] * 1200;
      //                      echo "Time:".$this->time."<br>";
      //                      echo "Solved:".$this->solved."<br>";
    }
  }
}

function s_cmp($A, $B)
{
  if ($A->total != $B->total) {
    return $A->total < $B->total ? 1 : -1;
  } else if ($A->solved == 0 && $B->solved == 0) {
    if (array_sum($A->p_wa_num) != array_sum($B->p_wa_num)) {
      return array_sum($A->p_wa_num) < array_sum($B->p_wa_num) ? 1 : -1;
    } else {
      return intval($A->user_id) > intval($B->user_id) ? 1 : -1;
    }
  } else {
    if ($A->solved != $B->solved) {
      return $A->solved < $B->solved ? 1 : -1;
    } else {
      return $A->time > $B->time ? 1 : -1;
    }
  }
}

// contest start time
if (!isset($_GET['cid'])) die("No Such Contest!");
$cid = intval($_GET['cid']);


$sql = "SELECT `start_time`,`title`,`end_time` FROM `contest` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;


$start_time = 0;
$end_time = 0;
if ($rows_cnt > 0) {
  //       $row=$result[0];
  $row = $result[0];
  $start_time = strtotime($row['start_time']);
  $end_time = strtotime($row['end_time']);
  $title = $row['title'];
}
if ($start_time == 0) {
  $view_swal = $MSG_NOT_EXISTED;
  require("template/error.php");
  exit(0);
}

if ($start_time > time()) {
  $view_swal = $MSG_PRIVATE_WARNING;
  require("template/error.php");
  exit(0);
}
$noip = (time() < $end_time) && (stripos($title, $OJ_NOIP_KEYWORD) !== false);
if (
  isset($_SESSION[$OJ_NAME . '_' . "administrator"]) ||
  isset($_SESSION[$OJ_NAME . '_' . "m$cid"]) ||
  isset($_SESSION[$OJ_NAME . '_' . "source_browser"]) ||
  isset($_SESSION[$OJ_NAME . '_' . "contest_creator"])
) $noip = false;
if ($noip) {
  $view_errors =  "<h2>$MSG_NOIP_WARNING</h2>";
  require("template/error.php");
  exit(0);
}
if (!isset($OJ_RANK_LOCK_PERCENT))
  $OJ_RANK_LOCK_PERCENT = 1;
$lock = $end_time - ($end_time - $start_time) * $OJ_RANK_LOCK_PERCENT;

//echo $lock.'-'.date("Y-m-d H:i:s",$lock);
$view_lock_time = $start_time + ($end_time - $start_time) * (1 - $OJ_RANK_LOCK_PERCENT);
$locked_msg = "";
if (time() > $view_lock_time && time() < $end_time + $OJ_RANK_LOCK_DELAY) {
  $locked_msg = "<h2>$MSG_RANK_LOCKED</h2><span class='alert alert-danger'>$MSG_RANK_LOCKED</span></h2>";
}

$sql = "SELECT count(1) as pbc FROM `contest_problem` WHERE `contest_id`=?";
$result = mysql_query_cache($sql, $cid);
if ($result) $rows_cnt = count($result);
else $rows_cnt = 0;

$row = $result[0];

// $row=$result[0];
$pid_cnt = intval($row['pbc']);


require("./include/contest_solutions.php");
//echo $sql;
//$result=pdo_query($sql);

$user_cnt = 0;
$user_name = '';
$U = array();
for ($i = 0; $i < $rows_cnt; $i++) {

  $row = $result[$i];


  $n_user = $row['user_id'];
  if (strcmp($user_name, $n_user)) {
    $user_cnt++;
    $U[$user_cnt] = new TM();

    $U[$user_cnt]->user_id = $row['user_id'];
    $U[$user_cnt]->nick = $row['nick'];

    $user_name = $n_user;
  }
  if ($row['result'] != 4 && $row['pass_rate'] >= 0.99) $row['pass_rate'] = 0;
  if (time() < $end_time + $OJ_RANK_LOCK_DELAY && $lock < strtotime($row['in_date']))
    $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, 0);
  else
    $U[$user_cnt]->Add($row['num'], strtotime($row['in_date']) - $start_time, $row['pass_rate']);
}
usort($U, "s_cmp");

////firstblood
$first_blood = array();
for ($i = 0; $i < $pid_cnt; $i++) {
  $first_blood[$i] = "";
}

$sql = "select s.num,s.user_id from solution s ,
        (select num,min(solution_id) minId from solution where contest_id=? and result=4 GROUP BY num ) c where s.solution_id = c.minId";
$fb = mysql_query_cache($sql, $cid);
if ($fb) $rows_cnt = count($fb);
else $rows_cnt = 0;

foreach ($fb as $row) {
  $first_blood[$row['num']] = $row['user_id'];
}




require("template/contestrank-oi.php");

if (file_exists('./include/cache_end.php'))
  require_once('./include/cache_end.php');
