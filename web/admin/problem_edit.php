<?php
require_once("admin-header.php");

if (!(isset($_SESSION[$OJ_NAME . '_' . 'administrator'])
    || isset($_SESSION[$OJ_NAME . '_' . 'problem_editor'])
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
    <?php if (isset($_GET['id'])) { ?>
        <div class='container'>
            <?php include("../template/nav.php") ?>
            <div class='jumbotron'>
                <div class='row lg-container'>
                    <?php require_once("sidebar.php") ?>
                    <div class='col-md-9 col-lg-10 p-0'>
                        <?php
                        echo "<center><h3>" . $MSG_PROBLEM . "-" .  $MSG_EDIT . "</h3></center>";
                        ?>

                        <div class="container">
                            <form method=POST action=problem_edit.php>
                                <?php
                                $sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
                                $result = pdo_query($sql, intval($_GET['id']));
                                $row = $result[0];
                                ?>

                                <input type=hidden name=problem_id value='<?php echo $row['problem_id'] ?>'>
                                <div class='form-inline'>
                                    <h3>
                                        <?php echo $row['problem_id'] ?>: <input class="input form-control" style='width:90%;height:auto;' type=text name=title value='<?php echo htmlentities($row['title'], ENT_QUOTES, "UTF-8") ?>'>
                                    </h3>
                                </div>

                                <div class='form-inline'>
                                    <?php echo $MSG_Time_Limit ?><br>
                                    <input class="input form-control" type=text name=time_limit size=20 value='<?php echo htmlentities($row['time_limit'], ENT_QUOTES, "UTF-8") ?>'> sec<br><br>
                                    <?php echo $MSG_Memory_Limit ?><br>
                                    <input class="input form-control" type=text name=memory_limit size=20 value='<?php echo htmlentities($row['memory_limit'], ENT_QUOTES, "UTF-8") ?>'> MB<br><br>
                                </div>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_Description . "</h4>" ?>
                                    <textarea id="tinymce0" class='form-control' rows=13 name=description cols=80><?php echo htmlentities($row['description'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_Input . "</h4>" ?>
                                    <textarea id="tinymce1" class='form-control' rows=13 name=input cols=80><?php echo htmlentities($row['input'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_Output . "</h4>" ?>
                                    <textarea id="tinymce2" class='form-control' rows=13 name=output cols=80><?php echo htmlentities($row['output'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_Sample_Input . "</h4>" ?>
                                    <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_input><?php echo htmlentities($row['sample_input'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_Sample_Output . "</h4>" ?>
                                    <textarea class="input input-large form-control" style="width:100%;" rows=13 name=sample_output><?php echo htmlentities($row['sample_output'], ENT_QUOTES, "UTF-8") ?></textarea><br><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_HINT . "</h4>" ?>
                                    <textarea id="tinymce3" class='form-control' rows=13 name=hint cols=80><?php echo htmlentities($row['hint'], ENT_QUOTES, "UTF-8") ?></textarea><br>
                                </p>

                                <p align=left>
                                    <?php echo "<h4>" . $MSG_BACKGROUND . "</h4>" ?>
                                    <input class="input form-control" style='width:90%;height:auto;' type=text name=background value='<?php echo htmlentities($row['background'], ENT_QUOTES, "UTF-8") ?>'>
                                    <br>
                                </p>

                                <p>
                                    <?php echo "<h4>" . $MSG_SPJ . "</h4>" ?>
                                <p><?php echo $MSG_HELP_SPJ ?></p>
                                <input type="radio" name="spj" value='0' <?php if (!$row['spj']) echo "checked" ?>> <?php echo $MSG_TRUE_FALSE[false] ?>
                                <span> / </span>
                                <input type="radio" name="spj" value='1' <?php if ($row['spj']) echo "checked" ?>> <?php echo $MSG_TRUE_FALSE[true] ?>
                                <br><br>
                                </p>
                                <p>
                                    <?php echo "<h4>" . $MSG_BLANK_FILLING . "</h4>" ?>
                                    <input type=radio id=blank_false name=blank value='0' checked> <?php echo $MSG_TRUE_FALSE[false] ?>
                                    <span> / </span>
                                    <input type=radio id=blank_true name=blank value='1'> <?php echo $MSG_TRUE_FALSE[true] ?>
                                    <br><br>
                                </p>
                                <p>
                                <div id='blank_code'>
                                    <h4><?php echo $MSG_BLANK_TEMPLATE ?></h4>
                                    <h5><?php echo $MSG_TEMPLATE_EXPLAIN ?></h5>
                                    <textarea hidden='hidden' id='multiline' name='blank_code' autocomplete='off'><?php echo $row['blank'] ?></textarea>
                                    <div class="editor-border" id=source style='height:300px;width:auto;margin-top:8px;'></div>
                                    <br>
                                </div>
                                </p>
                                <p align=left>
                                    <?php echo "<h4>$MSG_BLOCK_KEYWORD</h4>" ?>
                                <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
                                <input name=block class="form-control" data-role="tagsinput" value='<?php echo htmlentities(join(",", explode(" ", $row['block'])), ENT_QUOTES, "UTF-8") ?>'></input><br><br>
                                </p>
                                <p align=left>
                                    <?php echo "<h4>$MSG_ALLOW_KEYWORD</h4>" ?>
                                <h5><?php echo $MSG_HELP_KEYWORD ?></h5>
                                <input name=allow class="form-control" data-role="tagsinput" value='<?php echo htmlentities(join(",", explode(" ", $row['allow'])), ENT_QUOTES, "UTF-8") ?>'></input><br><br>
                                </p>
                                <p align=left>
                                    <?php echo "<h4>" . $MSG_SOURCE . "</h4>" ?>
                                    <input name=source class="form-control" data-role="tagsinput" value='<?php echo htmlentities(join(",", explode(" ", $row['source'])), ENT_QUOTES, "UTF-8") ?>'></input><br><br>
                                </p>
                                <div align=center>
                                    <?php require_once("../include/set_post_key.php"); ?>
                                    <input class='btn btn-default' type=submit value='<?php echo $MSG_SAVE ?>' name=submit>
                                </div>
                            </form>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <?php require_once("../template/js.php"); ?>
        <script>
            <?php if (is_null($row['blank'])) { ?>
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
                window.editor.layout();
            })
        </script>
        <script src='<?php echo $OJ_CDN_URL .  "include/" ?>bootstrap-tagsinput.min.js'></script>
        <script src="<?php echo $OJ_CDN_URL . "monaco/min/vs/" ?>loader.js"></script>
        <script>
            require.config({
                paths: {
                    vs: ['../monaco/min/vs']
                }
            });

            require(['vs/editor/editor.main'], function() {
                window.editor = monaco.editor.create(document.getElementById('source'), {
                    value: `<?php echo str_replace("`", "\`", $row['blank']) ?>`,
                    language: 'plain',
                    fontSize: "18px",
                });

                window.editor.getModel().onDidChangeContent((event) => {
                    $("#multiline").val(window.editor.getValue())
                });
            });

            window.onresize = function() {
                window.editor.layout();
            }
        </script>
        <?php require_once('../tinymce/tinymce.php'); ?>
</body>

<?php
    } else {
        $id = intval($_POST['problem_id']);

        $title = $_POST['title'];
        $title = str_replace(",", "&#44;", $title);

        $time_limit = $_POST['time_limit'];

        $memory_limit = $_POST['memory_limit'];

        $description = $_POST['description'];
        $description = str_replace("<p>", "", $description);
        $description = str_replace("</p>", "<br>", $description);
        $description = str_replace(",", "&#44;", $description);

        $input = $_POST['input'];
        $input = str_replace("<p>", "", $input);
        $input = str_replace("</p>", "<br>", $input);
        $input = str_replace(",", "&#44;", $input);

        $output = $_POST['output'];
        $output = str_replace("<p>", "", $output);
        $output = str_replace("</p>", "<br>", $output);
        $output = str_replace(",", "&#44;", $output);

        $sample_input = $_POST['sample_input'];
        $sample_output = $_POST['sample_output'];
        if ($sample_input == "") $sample_input = "\n";
        if ($sample_output == "") $sample_output = "\n";

        $hint = $_POST['hint'];
        $hint = str_replace("<p>", "", $hint);
        $hint = str_replace("</p>", "<br>", $hint);
        $hint = str_replace(",", "&#44;", $hint);

        $spj = $_POST['spj'];

        $source = join(" ", explode(",", trim($_POST['source'])));
        $allow = join(" ", explode(",", trim($_POST['allow'])));
        $block = join(" ", explode(",", trim($_POST['block'])));
        $blank = $_POST['blank_code'];

        if (substr($blank, -3) == "*%*") {
            $blank = substr($blank, 0, -3) . "\n";
        }

        $background = $_POST["background"];

        $title = ($title);
        $basedir = $OJ_DATA . "/$id";

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


        $sql = "SELECT * FROM `problem` WHERE `problem_id`=?";
        $result = pdo_query($sql, $id);
        $row = $result[0];

        $log = array();
        $compare = array(
            "title", "time_limit", "memory_limit", "description",
            "input", "output", "sample_input", "sample_output",
            "hint", "source", "spj", "background", "blank", "allow", "block"
        );
        foreach ($compare as $key) {
            if ($row[$key] != ${$key}) {
                $log[] = $key;
            }
        }
        $logs = join(", ", $log);


        $sql = "UPDATE `problem` SET `title`=?,`time_limit`=?,`memory_limit`=?, `description`=?,`input`=?,`output`=?,`sample_input`=?,`sample_output`=?,`hint`=?,`source`=?,`spj`=?, `background`=?, `in_date`=NOW(), `blank`=NULL, `allow`=NULL, `block`=NULL WHERE `problem_id`=?";
        pdo_query($sql, $title, $time_limit, $memory_limit, $description, $input, $output, $sample_input, $sample_output, $hint, $source, $spj, $background, $id);

        if ($_POST['blank'] == '1') {
            $sql = 'UPDATE `problem` set `blank`=? where `problem_id`=?';
            pdo_query($sql, $blank, $id);
        }

        if ($allow) {
            $sql = 'UPDATE `problem` set `allow`=? where `problem_id`=?';
            pdo_query($sql, $allow, $id);
        }

        if ($block) {
            $sql = 'UPDATE `problem` set `block`=? where `problem_id`=?';
            pdo_query($sql, $block, $id);
        }

        $ip = getRealIP();
        $sql = "INSERT INTO `oplog` (`target`,`user_id`,`operation`,`ip`) VALUES (?,?,?,?)";
        pdo_query($sql, "p$id", $_SESSION[$OJ_NAME . '_' . 'user_id'], "edit $logs", $ip);
?>
    <div class='container'>
        <?php include("../template/nav.php") ?>
        <div class='jumbotron'>
            <div class='row lg-container'>
                <?php require_once("sidebar.php") ?>
                <div class='col-md-9 col-lg-10 p-0'>
                    <?php
                    echo "<center><h3>" . $MSG_PROBLEM . "-" .  $MSG_EDIT . "</h3></center>";
                    ?>
                    <div class="container">
                        <p><?php echo $MSG_EDIT_SUCCESS ?></p>
                        <a href='../problem.php?id=<?php echo $id ?>'><?php echo $MSG_SEE ?></a>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <?php require_once("../template/js.php"); ?>
    <script>
        swal({
            title: "<?php echo $MSG_SUCCESS ?>",
            icon: "success",
            text: "<?php echo $MSG_EDIT_SUCCESS ?>",
            buttons: {
                roll: {
                    text: "<?php echo $MSG_SEE ?>",
                    value: "href"
                },
                confirm: true
            }
        }).then((value) => {
            if (value == "href") {
                window.location.href = "../problem.php?id=<?php echo $id ?>"
            }
        })
    </script>
<?php } ?>

</body>

</html>