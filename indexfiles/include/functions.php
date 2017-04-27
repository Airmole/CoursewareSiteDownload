<?php
/**
 * Enable / disable visual elements
 */
$SHOW_HEADER = true;  // show header above the main table
$SHOW_BREADCRUMBS = true;  // show breadcrumbs (location) above the main table
$SHOW_TAB_HEADER_ROW = true;  // show the tabel header row
$SHOW_ICON = true;  // show icon field
$SHOW_SIZE = true;  // show size field
$SHOW_MODIFIED = true;  // show modification time field
$SHOW_EXEC_TIME = true;  // show the script execution time at the bottom of the main table
$SHOW_INFO_ICON = true;  // show Colorbox pop-up (info button is in the toolbar)
$SHOW_PRINT_ICON = true;  // make pages available for printing (print button is in the toolbar)

/**
 * File list properties
 */
$NEW_FILE_AGE = 7;  // number of days that a file is considered new (has 'new' shown near it)

$HIDDEN_FILES_MASKS = array(    // list of regexp masks that if matched cause an entry to be hidden
    "/^(.*)\/\.$/",             // mask for hiding "." entries in any directory
    "/^\.\/index\.php$/",       // mask for hiding main script file "index.php"
    "/^\.\/header\.php$/",       // mask for hiding main script file "header.php"
    "/^\.\/indexfiles(.*)/",    // mask for hiding directory "indexfiles" and all its contents
    "/^\.\/\.git(.*)/",         // mask for hiding directory ".git" and all its contents
    "/^\.\/\.idea(.*)/",        // mask for hiding directory ".idea" and all its contents
    "/^(.*)\/\.ftpquota$/",     // mask for hiding ".ftpquota" entries in any directory
);

$EXT2ICO_MAPPING = array(  // extension to icon mapping
    "php" => "docweb.gif",
    "htm" => "docweb.gif",
    "html" => "docweb.gif",
    "shtml" => "docweb.gif",
    "dhtml" => "docweb.gif",
    "ace" => "archive.gif",
    "gz" => "archive.gif",
    "zip" => "archive.gif",
    "rar" => "archive.gif",
    "doc" => "doc.gif",
    "xls" => "doc.gif",
    "ppt" => "doc.gif",
    "pps" => "doc.gif",
    "rtf" => "doc.gif",
    "txt" => "text.gif",
    "pdf" => "pdf.gif",
    "c" => "text.gif",
    "cpp" => "text.gif",
    "java" => "text.gif",
    "class" => "class.gif",
    "exe" => "exe.gif",
    "mp3" => "media.gif",
    "wma" => "media.gif",
    "wav" => "media.gif",
    "avi" => "media.gif",
    "mpg" => "media.gif",
    "chm" => "unknown.gif",
    "hlp" => "unknown.gif",
    "css" => "unknown.gif",
    "js" => "text.gif",
    "gif" => "img.gif",
    "jpg" => "img.gif",
    "png" => "img.gif",
    "bmp" => "img.gif",
    "jpeg" => "img.gif",
    "swf" => "media.gif",
    "ttf" => "img.gif"
);


/**
 * Functions used by "index.php"
 */
function sanitize_input_dir($input_dir) {
    $ROOT = ".";
    $breadcrumbs = explode("/", $input_dir);

    /* all input paths must start with "." upward traversal is not allowed (".." cannot be used in path) */
    if ($breadcrumbs[0] === $ROOT and !in_array("..", $breadcrumbs) and file_exists($input_dir)) {
        return $input_dir;
    } else {
        return $ROOT."/";
    }
}


function fdscript_url_encoding($path, $include_root=false) {
    $breadcrumbs = explode("/", $path);
    $encoded_url = "";
    $i = $include_root ? 0 : 1;
    for (; $i < count($breadcrumbs); $i++) {
        $encoded_url .= $include_root ? urlencode($breadcrumbs[$i]) : $breadcrumbs[$i];
        $encoded_url .= $breadcrumbs[$i] != "" ? "/" : "";
    }
    return $encoded_url;
}

function hide_entry($dirpath, $ent) {
    global $HIDDEN_FILES_MASKS;
    $full_path = $dirpath.$ent;
    foreach($HIDDEN_FILES_MASKS as $mask) {
        if (preg_match($mask, $full_path, $matches)) {
            return true;
        }
    }
    return false;
}

function format_file_size($size) { // $size is in bytes
    $SIZE_1MB = 1024.00 * 1024.00;
    $SIZE_1KB = 1024.00;
    if ($size >= $SIZE_1MB ) {
        return strval(round($size/$SIZE_1MB, 3))." MB";
    } else if ($size >= $SIZE_1KB) {
        return strval(round($size/$SIZE_1KB, 3))." KB";
    } else {
        return strval($size)." B";
    }
}


function AF_rem_empty_str_items($item) {
    return $item != "";
}


function name_compare($entry_a, $entry_b, $stop=false, $factor=1) {
    $ret_val = $factor * strnatcasecmp($entry_a["name"], $entry_b["name"]);
    if ($ret_val == 0 && !$stop) {
        return time_compare_r($entry_a, $entry_b);
    }
    return $ret_val;
}


function name_compare_r($entry_a, $entry_b, $stop=false) {
    return name_compare($entry_a, $entry_b, $stop, $factor=-1);
}


function time_compare($entry_a, $entry_b, $stop=false, $factor=1) {
    $ret_val = 0;
    if ($entry_a["mtime"] < $entry_b["mtime"]) {
        $ret_val = $factor * -1;
    } else if ($entry_a["mtime"] > $entry_b["mtime"]) {
        $ret_val = $factor * 1;
    } else if (! $stop) {
        $ret_val = name_compare($entry_a, $entry_b, $stop=true);
    }
    return $ret_val;
}


function time_compare_r($entry_a, $entry_b, $stop=false) {
    return time_compare($entry_a, $entry_b, $stop, $factor=-1);
}


function size_compare($entry_a, $entry_b, $stop=false, $factor=1) {
    $ret_val = 0;
    if ($entry_a["size"] < $entry_b["size"]) {
        $ret_val = $factor * -1;
    } else if ($entry_a["size"] > $entry_b["size"]) {
        $ret_val = $factor * 1;
    } else if (! $stop) {
        $ret_val = name_compare($entry_a, $entry_b, $stop=true);
    }
    return $ret_val;
}


function size_compare_r($entry_a, $entry_b, $stop=false) {
    return size_compare($entry_a, $entry_b, $stop, $factor=-1);
}


function get_script_name_and_version() {
    return "fdscript 1.4 build 20160625";
}


/******************************************
 ** This function is used to determine the
 ** script's execution time. It is copied
 ** from the PHP help file and DOES NOT
 ** belong to me.
 ******************************************/
function get_microtime()	{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>
