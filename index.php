<?php
date_default_timezone_set("Europe/Bucharest");
$date_format = "Y.m.d H:i";
$images_directory = "./indexfiles/images/";

// include configuration and functions
@include("./indexfiles/include/functions.php");

$time_start = get_microtime();  // variable used to determine the script execution time

$dirpath = "";
$dot_dot_path = false;
$row_class = array('odd', 'even');

if (isset($_GET["dirpath"])) {  // use input value (if provided)
    $dirpath = $_GET["dirpath"];
}
$dirpath = sanitize_input_dir($dirpath);

$dir_list_prefix = fdscript_url_encoding($dirpath, $include_root=true);
$file_download_prefix = fdscript_url_encoding($dirpath);

$order = 3;  // default ordering (mtime desc - newest first)
if (isset($_GET["order"])) {
    $order = intval($_GET["order"]);  // use input value (if provided)
}

$directories = array();
$files = array();
$all_entries = scandir($dirpath);
foreach ($all_entries as $ent) {
    if (strcmp($ent, "..") == 0) {
        $level_up_date = filemtime($dirpath."/".$ent);
    }
    if (!hide_entry($dirpath, $ent)) {
        $file_size = filesize($dirpath."/".$ent);
        $file_mtime = filemtime($dirpath."/".$ent);
        if (is_dir($dirpath."/".$ent)) {
            $directories[] = array(
                "name" => $ent,
                "size" => 0,
                "mtime" => $file_mtime,
            );
        } else {
            $files[] = array(
                "name" => $ent,
                "size" => $file_size,
                "mtime" => $file_mtime,
            );
        }
    }
}

$order_asc = $images_directory."sort/arr_up.gif";
$order_desc = $images_directory."sort/arr_down.gif";
$arrow_name = false;
$arrow_mtime = false;
$arrow_size = false;

switch ($order) {
    case 0:  // name asc
        $new_name_order = 1;
        $new_mtime_order = 2;
        $new_size_order = 4;
        $arrow_name = "<img src=\"$order_asc\"/>";
        uasort($directories, "name_compare");
        uasort($files, "name_compare");
        break;
    case 1: // name desc
        $new_name_order = 0;
        $new_mtime_order = 2;
        $new_size_order = 4;
        $arrow_name = "<img src=\"$order_desc\"/>";
        uasort($directories, "name_compare_r");
        uasort($files, "name_compare_r");
        break;
    case 2: // time asc
        $new_name_order = 0;
        $new_mtime_order = 3;
        $new_size_order = 4;
        $arrow_mtime = "<img src=\"$order_asc\"/>";
        uasort($directories, "time_compare");
        uasort($files, "time_compare");
        break;
    case 3: // time desc
        $new_name_order = 0;
        $new_mtime_order = 2;
        $new_size_order = 4;
        $arrow_mtime = "<img src=\"$order_desc\"/>";
        uasort($directories, "time_compare_r");
        uasort($files, "time_compare_r");
        break;
    case 4: // size asc
        $new_name_order = 0;
        $new_mtime_order = 2;
        $new_size_order = 5;
        $arrow_size = "<img src=\"$order_asc\"/>";
        uasort($directories, "size_compare");
        uasort($files, "size_compare");
        break;
    case 5:  // size desc
        $new_name_order = 0;
        $new_mtime_order = 2;
        $new_size_order = 4;
        $arrow_size = "<img src=\"$order_desc\"/>";
        uasort($directories, "size_compare_r");
        uasort($files, "size_compare_r");
        break;
    default:  // name asc
        $new_name_order = 1;
        $new_mtime_order = 2;
        $new_size_order = 4;
        $arrow_name = "<img src=\"$order_asc\"/>";
        uasort($directories, "name_compare");
        uasort($files, "name_compare");
}
?>

<html>
<head>
<meta charset="utf-8"> <!--这行是咱们自己加上的，不然汉化后中文就是乱码。-->
    <title>信实1601课件下载站</title>
    <meta name="keywords" content="信实1601，课件，下载，凩茻MUMU，MUMU" />
    <meta name="description" content="欢迎同学们前来下载课件。Welcome to download the courseware." />

    <link href="./indexfiles/colorbox.css" rel="stylesheet">
    <link href="./indexfiles/fdscript.css" rel="stylesheet" media="screen">
    <script src="./indexfiles/jquery-1.12.4.min.js"></script>
    <script src="./indexfiles/jquery.colorbox-min.js"></script>
    <script language="javascript">
        $(document).ready(function(){
            $(".inline").colorbox({inline:true, width:"20%"});
        });
    </script>
    <!--统计代码-->
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?c141a61096885f4ac4fc5def795d6a1f";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>
<!--统计代码END -->
<meta name="keywords" content="凩茻MUMU,信实1601,信实,MUMU0934,MUMU,北京科技大学天津学院,北科天院,贝壳田园" />
<meta name="description" content="这是凩茻MUMU的主页。Powered by MUMU." />
</head>

<body>

<!-- START OF THE MAIN TABLE-->
<table id="wrapper">
<?php if ($SHOW_HEADER) { ?>
    <tr>
        <td><?php @include("./header.php"); ?></td>
    </tr>
<?php } ?>

<?php if ($SHOW_BREADCRUMBS) { ?>
    <tr>
        <td>
            <table id="top_content">
                <!-- START PRINTING CURRENT DIRECTORY NAME -->
                <tr>
                    <td>
<?php
$path_elements = array_filter(explode("/", $dirpath), "AF_rem_empty_str_items");
echo "<a href=\"index.php?dirpath=&order=".$order."\" class=\"breadcrumb\">[根目录]</a>";
for ($i=1; $i < count($path_elements); $i++) {
    $path_link = fdscript_url_encoding(implode("/", array_slice($path_elements, 0, $i + 1)), $include_root=true);
    echo " / <a href=\"index.php?dirpath=".$path_link."&order=".$order."\" class=\"breadcrumb\">[".$path_elements[$i]."]</a>";
}
$dot_dot_path = fdscript_url_encoding(implode("/", array_slice($path_elements, 0, count($path_elements) - 1)), $include_root=true);
?>
                    </td>
                </tr>
                <!-- STOP PRINTING CURRENT DIRECTORY NAME -->
            </table>
        </td>
    </tr>
<?php } ?>
    <tr>
        <td>

<!-- START PRINTING DIRECTORIES & FILES -->
<table id="main_content">

<!-- START PRINTING TABLE HEADERS -->
<?php if ($SHOW_TAB_HEADER_ROW) { ?>
    <thead>
        <?php if ($SHOW_ICON) { ?>
            <td><!--ICO--></td>
        <?php } ?>

        <td class="sortable" <?php echo "onclick=\"window.location='index.php?dirpath=".$dir_list_prefix."&order=".$new_name_order."'\"";?>>
            <?php
            if ($arrow_name === false) {
                echo "文件";
            } else {
                echo "文件 ".$arrow_name;
            }
            ?>
        </td>

<!--        <td>EXT</td>-->

        <?php if ($SHOW_SIZE) {?>
            <td class="sortable" <?php echo "onclick=\"window.location='index.php?dirpath=".$dir_list_prefix."&order=".$new_size_order."'\"";?>>
                <?php
                if ($arrow_size === false) {
                    echo "大小";
                } else {
                    echo "大小 ".$arrow_size;
                }
                ?>
            </td>
        <?php } ?>

        <?php if ($SHOW_MODIFIED) {?>
            <td class="sortable" <?php echo "onclick=\"window.location='index.php?dirpath=".$dir_list_prefix."&order=".$new_mtime_order."'\"";?> >
                <?php
                if ($arrow_mtime === false) {
                    echo "更新";
                } else {
                    echo "更新 ".$arrow_mtime;
                }
                ?>
            </td>
        <?php } ?>
    </thead>
<?php } ?>

<?php
// variable used to select row background color
$j = 0;

// ".." //
if ($dot_dot_path) {
    echo "<tr class=\"".$row_class[$j%2]."\">";
    if ($SHOW_ICON) {
        echo "<td class=\"fico\"><img src=\"".$images_directory."extensions/back.gif\"></td>";
    }
    echo "<td class=\"fname\"><a href=\"index.php?dirpath=".$dot_dot_path."&order=".$order."\" class=\"directory\">[..]</a></td>";
    if ($SHOW_SIZE) {
        echo "<td class=\"fsize\"><span class=\"text\">&lt;文件夹&gt;</span></td>";
    }
    if ($SHOW_MODIFIED) {
        echo "<td class=\"fmtime\">".date($date_format, $level_up_date)."</td>";
    }
    echo "</tr>";
}

// DIRECTORIES //
foreach($directories as $ent) {
    $ent_name = $ent["name"];
    $ent_full_name = $dir_list_prefix.urlencode($ent["name"])."/";
    $ent_size = round($ent["size"]/1024.00, 3);
    $ent_date = date($date_format, $ent["mtime"]);

    if (strcmp($ent_name, "..")) {
        $j++;  // variable used to select row background color

        echo "<tr class=\"".$row_class[$j%2]."\">";
        if ($SHOW_ICON) {
            echo "<td class=\"fico\"><img src=\"".$images_directory."extensions/dir.gif\"></td>";
        }
        echo "<td class=\"fname\"><a href=\"index.php?dirpath=".$ent_full_name."&order=".$order."\" class=\"directory\">[".$ent_name."]</a>";
        if (((time() - $ent["mtime"]) / 1E+5) < $NEW_FILE_AGE) {
            echo " <span class=\"recent\">new</span>";
        }
        echo "</td>";
        if ($SHOW_SIZE) {
            echo "<td class=\"fsize\">&lt;文件夹&gt;</td>";
        }
        if ($SHOW_MODIFIED) {
            echo "<td class=\"fmtime\">".$ent_date."</td>";
        }
        echo "</tr>";
    }
}

// FILES //
foreach($files as $ent) {
    $ent_name = $ent["name"];
    $ent_full_name = $file_download_prefix.$ent["name"];
    $ent_size = format_file_size($ent["size"]);
    $ent_date = date($date_format, $ent["mtime"]);

    $j++;

    // split the filename into name and extension
    $split_name_ext = explode(".", $ent_name);

    // store the file extension
    $file_ext = (count($split_name_ext) - 1 != 0) ? $split_name_ext[count($split_name_ext)-1] : "";
    $lower_ext = strtolower($file_ext);

    $icon = "unknown.gif";  // default icon
    if (array_key_exists($lower_ext, $EXT2ICO_MAPPING)) {  // if there is a custom icon for this extension, use it
        $icon = $EXT2ICO_MAPPING[$lower_ext];
    }

    echo "<tr class=\"".$row_class[$j%2]."\">";
    // print the icon
    if ($SHOW_ICON) {
        echo "<td class=\"fico\"><img src=\"".$images_directory."extensions/".$icon."\"></td>";
    }

    // print the filename
    echo "<td class=\"fname\"><a href=\"".$ent_full_name."\" class=\"file\">".$ent_name."</a>";
    if (((time() - $ent["mtime"]) / 1E+5) < $NEW_FILE_AGE) {
        echo " <span class=\"recent\">new</span>";
    }
    echo "</td>";

    // TODO start - force download ?!?!
//    if (in_array($lower_ext, $ext_not_to_be_dloaded)) {
        // for the files that should not be downloaded use a direct link
//        echo "<a href=\"".urlencode($ent_full_name)."\" class=\"file\">".$ent_name."</a>";
//    } else {
        // for the files that should be downloaded use the 'download.php' script
//        echo "<a href=\"download.php?fname=".urlencode($ent_full_name)."\" class=\"file\">".$ent_name."</a>";
//    }
    // TODO end - force download ?!?!

    // print the file size
    if ($SHOW_SIZE) {
        echo "<td class=\"fsize\">".$ent_size."</td>";
    }

    // print the date the file was last modified
    if ($SHOW_MODIFIED) {
        echo "<td class=\"fmtime\">".$ent_date."</td>";
    }

    // print the file type description
    echo "</tr>";
}
?>
</table>
<!-- END PRINTING DIRECTORIES & FILES -->
    </td>

<!-- START TOOLBAR -->
<td>
    <table id="toolbar">
        <tr>
            <td>
                <a class="inline" href="#inline_content">
                    <img src="<?php echo $images_directory."toolbox/info.gif"; ?>">
                </a>
            </td>
        </tr>
        <?php if ($SHOW_PRINT_ICON) { ?>
            <tr>
                <td>
                    <a href="#" onclick="window.print()">
                        <img src="<?php echo $images_directory."toolbox/print.gif"; ?>">
                    </a>
                </td>
            </tr>
        <?php } ?>
    </table>
</td>
<!-- STOP TOOLBAR -->
</tr>

<tr>
    <td>
        <table id="bottom_content">
            <tr>
                <?php if ($SHOW_EXEC_TIME) { ?>
                    <!-- START PRINTING EXEC TIME -->
                    <td>
                        <?php printf("本次耗时 %.4f 秒！", get_microtime() - $time_start); ?>
                    </td>
                    <!-- STOP PRINTING EXEC TIME -->
                <?php } ?>
            </tr>
        </table>
    </td>
</tr>

</table>
<!-- STOP OF THE MAIN TABLE-->

<!-- This contains the hidden content for inline calls -->

<div style='display:none'>
    <div id="inline_content">
        Powered by  <strong><a href="https://mumu0934.github.io">凩茻MUMU</a></strong><br /> 如需申请资源可以i联系：<br />
        <img src="./indexfiles/images/qrcode_weixin.png" width=" 150px" height=" 150px">
    </div>
</div>





<!--这个DIV是整个footer-->
<div style="height: 300px; width:100%; background-color: #34495e; font-family: 黑体;" >
<div style="width: 950px; margin:0 auto; overflow:hidden;">  <!--为了追求PC端居中-->

<div style="float: left;padding: 60px 40px  0px 40px; color:white ;width: 200px;" ><!--Contact us 部分DIV-->
<b>Contact us</b><hr><ul>
    <li><p><img src="./indexfiles/images/sina_weibo_16px.png" alt="Weibo"><a href="http://weibo.com/weizhengning"> Weibo</a></p></li>
    <li><p><img src="./indexfiles/images/github_16px.png" alt="Github"><a href="https://github.com/MUMU0934"> Github</a></p></li>
    <li><p><img src="./indexfiles/images/blog_16px.png" alt="Blog"><a href="https://mumu0934.github.io"> Blog</a></p></li>
    <li><p><img src="./indexfiles/images/QQ_Penguin_16px.png" alt="QQ"> QQ:745362916</p></li>
    <li><p><img src="./indexfiles/images/wechat_16px.png" alt="wechat"> Wechat:QQ0934</p></li>
</ul>
</div> <!--Contact us 部分DIV   END -->

<!--这是About 部分的div -->
<div style="float: left;padding: 60px 40px  0px 0px; background-color: #34495e; color:white ;width: 200px;" >
<b>About</b><hr>
<p>本站由 <strong><a href="https://mumu0934.github.io">凩茻MUMU</a></strong> 同学负责运营维护。如果您觉得做的还不错，可以点击下方的“赏”字进行打赏。<br>
   <div name="dashmain" id="dash-main-id-87912e" class="dash-main-3 87912e-1"></div><script type="text/javascript" charset="utf-8" src="https://www.dashangcloud.com/static/ds-2.0.js"></script></p>
</div>
<!--About 部分的div   END-->

<!--信实公众号部分DIV-->
<div style="float: left;padding: 42px 40px 0px 0px; color:white ;width: 150px; text-align: left;">
<b>微信公众号</b><p><hr></p>
  <a href="http://weixin.sogou.com/weixin?type=1&s_from=input&query=%E4%BF%A1%E5%AE%9E1601&ie=utf8&_sug_=n&_sug_type_="   target="_blank"><img src="./indexfiles/images/qrwechat.jpg" alt="微信公众号" width="150px" ; height="150px"></a>  <br>
</div>
<!--信实公众号部分DIV   END-->

<!--这是Copyright 部分的div -->
<div style="float: left;padding: 50px 40px  0px 0px; color:white ;width: 200px;" >
<b>Copyright</b><hr>

<p style="text-align:center;">版权所有 © 凩茻MUMU</p>
<p style="text-align:center;">陇ICP备17001242号-1</p>
<p style="text-align:center;"><a href="http://mumu0934.cn">mumu0934.cn</a></p>

</div>
<!--About 部分的div   END-->
</div> <!--为了追求PC端居中END-->
</div>
<!--整个footer  END -->

</body>
</html>
