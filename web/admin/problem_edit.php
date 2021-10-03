<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>Edit Problem</title>
</head>
<hr>

<?php
require_once("../include/db_info.inc.php");
require_once("admin-header.php");
require_once("../include/my_func.inc.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) {
  echo "<a href='../loginpage.php'>Please Login First!</a>";
  exit(1);
}

echo "<center><h3>" . "Edit-" . $MSG_PROBLEM . "</h3></center>";
include_once("kindeditor.php");
?>

<body leftmargin="30">
  <div class="container">
    <?php
    if (isset($_GET['id'])) {; //require_once("../include/check_get_key.php");
    ?>

      <form method=POST action=problem_edit.php>
        <?php
        $sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
        $result = pdo_query($sql, intval($_GET['id']));
        $row = $result[0];
        ?>

        <input type=hidden name=problem_id value='<?php echo $row['problem_id'] ?>'>
        <p align=left>
          <center>
            <h3>
              <?php echo $row['problem_id'] ?>: <input class="input input-xxlarge" style='width:90%;height:auto;' type=text name=title value='<?php echo htmlentities($row['title'], ENT_QUOTES, "UTF-8") ?>'>
            </h3>
          </center>
        </p>

        <p align=left>
          <?php echo $MSG_Time_Limit ?><br>
          <input class="input input-mini" type=text name=time_limit size=20 value='<?php echo htmlentities($row['time_limit'], ENT_QUOTES, "UTF-8") ?>'> sec<br><br>
          <?php echo $MSG_Memory_Limit ?><br>
          <input class="input input-mini" type=text name=memory_limit size=20 value='<?php echo htmlentities($row['memory_limit'], ENT_QUOTES, "UTF-8") ?>'> MB<br><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
          <textarea class="kindeditor" rows=13 name=description cols=80><?php echo htmlentities($row['description'], ENT_QUOTES, "UTF-8") ?></textarea><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
          <textarea class="kindeditor" rows=13 name=input cols=80><?php echo htmlentities($row['input'], ENT_QUOTES, "UTF-8") ?></textarea><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
          <textarea class="kindeditor" rows=13 name=output cols=80><?php echo htmlentities($row['output'], ENT_QUOTES, "UTF-8") ?></textarea><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
          <textarea class="input input-large" style="width:100%;" rows=13 name=sample_input><?php echo htmlentities($row['sample_input'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
          <textarea class="input input-large" style="width:100%;" rows=13 name=sample_output><?php echo htmlentities($row['sample_output'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
        </p>

        <p align=left>
          <?php echo "<h4>" . $MSG_HINT . "</h4>" ?>
          <textarea class="kindeditor" rows=13 name=hint cols=80><?php echo htmlentities($row['hint'], ENT_QUOTES, "UTF-8") ?></textarea><br>
        </p>

        <p>
          <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
          <?php echo "否 " ?><input type=radio name=spj value='0' checked><?php echo "/ 是 " ?><input type=radio name=spj value='1'><br><br>
        </p>
        <p>
          <?php echo "<h4>" . "代码填空" . "</h4>" ?>
          <?php echo "否 " ?><input type=radio id=blank_false name=blank value='0' checked><?php echo "/ 是 " ?><input type=radio id=blank_true name=blank value='1'><br><br>
        </p>
        <p>
        <div id='blank_code'>
          <h4>待填空代码</h4>
          <h5>单行填空请用%*%表示，多行填空用*%*表示，一个问题仅支持一个多行填空</h5>
          <textarea hidden='hidden' id='multiline' name='blank_code' autocomplete='off'></textarea>
          <pre id=source style='height:150px;width:auto;font-size:13pt;margin-top:8px;'><?php echo htmlentities($row['blank'], ENT_QUOTES, "UTF-8") ?></pre>
        </div>
        </p>
        <p align=left>
          <?php echo "<h4>禁用关键词</h4>" ?>
          <input name=block style="width:100%;" class=form-control value='<?php echo htmlentities($row['block'], ENT_QUOTES, "UTF-8") ?>'></input><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>必须关键词</h4>" ?>
          <input name=allow style="width:100%;" class=form-control value='<?php echo htmlentities($row['allow'], ENT_QUOTES, "UTF-8") ?>'></input><br><br>
        </p>
        <p align=left>
          <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
          <input name=source style="width:100%;" class=form-control value='<?php echo htmlentities($row['source'], ENT_QUOTES, "UTF-8") ?>'></input><br><br>
        </p>

        <div align=center>
          <?php require_once("../include/set_post_key.php"); ?>
          <input type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
        </div>
      </form>

    <?php
    } else {
      require_once("../include/check_post_key.php");
      $id = intval($_POST['problem_id']);

      if (!(isset($_SESSION[$OJ_NAME . '_' . "p$id"]) || isset($_SESSION[$OJ_NAME . '_' . 'administrator']) || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor']))) exit();

      $title = $_POST['title'];
      $title = str_replace(",", "&#44;", $title);

      $time_limit = $_POST['time_limit'];

      $memory_limit = $_POST['memory_limit'];

      $description = $_POST['description'];
      $description = str_replace("<p>", "", $description);
      $description = str_replace("</p>", "<br />", $description);
      $description = str_replace(",", "&#44;", $description);

      $input = $_POST['input'];
      $input = str_replace("<p>", "", $input);
      $input = str_replace("</p>", "<br />", $input);
      $input = str_replace(",", "&#44;", $input);

      $output = $_POST['output'];
      $output = str_replace("<p>", "", $output);
      $output = str_replace("</p>", "<br />", $output);
      $output = str_replace(",", "&#44;", $output);

      $sample_input = $_POST['sample_input'];
      $sample_output = $_POST['sample_output'];
      if ($sample_input == "") $sample_input = "\n";
      if ($sample_output == "") $sample_output = "\n";

      $hint = $_POST['hint'];
      $hint = str_replace("<p>", "", $hint);
      $hint = str_replace("</p>", "<br />", $hint);
      $hint = str_replace(",", "&#44;", $hint);

      $source = $_POST['source'];
      $spj = $_POST['spj'];

      $allow = trim($_POST['allow']);
      $block = trim($_POST['block']);
      $title = ($title);
      $description = RemoveXSS($description);
      $input = RemoveXSS($input);
      $output = RemoveXSS($output);
      $hint = RemoveXSS($hint);
      $basedir = $OJ_DATA . "/$id";

      echo "题目已更新！<br>";

      if ($sample_input && file_exists($basedir . "/sample.in")) {
        //mkdir($basedir);
        $fp = fopen($basedir . "/sample.in", "w");
        fputs($fp, preg_replace("(\r\n)", "\n", $sample_input));
        fclose($fp);

        $fp = fopen($basedir . "/sample.out", "w");
        fputs($fp, preg_replace("(\r\n)", "\n", $sample_output));
        fclose($fp);
      }

      $spj = intval($spj);

      $sql = "UPDATE `problem` SET `title`=?,`time_limit`=?,`memory_limit`=?, `description`=?,`input`=?,`output`=?,`sample_input`=?,`sample_output`=?,`hint`=?,`source`=?,`spj`=?,`in_date`=NOW(),`blank`=NULL,`allow`=NULL,`block`=NULL WHERE `problem_id`=?";

      @pdo_query($sql, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $id);
      if ($_POST['blank'] == '1') {
        $blank_code = $_POST['blank_code'];
        $sql = 'update `problem` set `blank`=? where `problem_id`=?';
        pdo_query($sql, $blank_code, $id);
      }
      if ($allow != '') {
        $sql = 'update `problem` set `allow`=? where `problem_id`=?';
        pdo_query($sql, $allow, $id);
      }
      if ($block != '') {
        $sql = 'update `problem` set `block`=? where `problem_id`=?';
        pdo_query($sql, $block, $id);
      }
      echo "编辑成功！<br>";
      echo "<a href='../problem.php?id=$id'>查看问题</a>";
    }
    ?>
  </div>
</body>
<script>
  <?php if ($row['blank'] == NULL) { ?>
    $("#blank_code").hide();
    $("#blank_false").click()
  <?php } else { ?>
    $("#blank_true").click()
  <?php } ?>
  $("#blank_false").click(function() {
    $("#blank_code").hide();
  })
  $("#blank_true").click(function() {
    $("#blank_code").show();
  })
</script>
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.4.12/src-min-noconflict/ace.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ace-builds@1.4.12/src-min-noconflict/ext-language_tools.min.js"></script>
<script>
  ace.require("ace/ext/language_tools");
  var editor = ace.edit("source");
  editor.setTheme("ace/theme/chrome");
  editor.setOptions({
    enableBasicAutocompletion: true,
    enableSnippets: true,
    enableLiveAutocompletion: true
  });
  editor.session.setTabSize(4);
  $("#multiline").val(editor.getValue())
  editor.session.on('change', function(delta) {
    $("#multiline").val(editor.getValue())
  });
</script>

</html>