<?php
/**
 * --------------------------------------------------------------------------- 
 * -= YOU MAY NOT REMOVE OR CHANGE THIS NOTICE =-                 
 * --------------------------------------------------------------------------- 
 * ApPHP image upload plugin for TinyMCE editor
 * License      : GNU LGPL v.3                                                
 * Copyright    : ApPHP Directy CMF (c) 2013 - 2017, All rights reserved.
 * Last changes : 10.01.2017
*/

/* Available options: "full" or "relative" */ 
$mode = 'relative'; 

/**
 * Framework directory
 * This varibale includes a relative path from application directory to the framework directory.
 * By default it defined for the case when application and framework are both placed in the same level:
 * site/
 *    - framework/
 *    - application/
 * If you change framework directory placement don't forget to change value of $frameworkDir
*/
$frameworkDir = '';

/* Base directory */
if($mode == 'full'){
    $baseDir = str_ireplace('assets\vendors\tinymce\plugins\imageupload', '', dirname(__FILE__));
    $baseDirFull = $baseDir;    
}else{
    $baseDir = '../../../../../';
    $baseDirFull = dirname(__FILE__).'/'.$baseDir;
}

////////////////////////////////////////////////////////////////////////////////
defined('APPHP_PATH') || define('APPHP_PATH', $baseDirFull);
// directory separator
defined('DS') || define('DS', DIRECTORY_SEPARATOR);
// production | debug | demo | test | hidden
defined('APPHP_MODE') or define('APPHP_MODE', 'hidden'); 

// application and framework in the same directory level
$apphp = $baseDirFull.$frameworkDir.'framework/Apphp.php';
$config = APPHP_PATH.'/protected/config/';

require_once($apphp);
A::init($config)->run();

$isDemo = false;
////////////////////////////////////////////////////////////////////////////////

$privilege_category = CFilter::sanitize('dbfield', A::app()->getSession()->get('privilege_category'));

// Check common access rights
if(!CAuth::isLoggedIn()){
    echo '<html>';
    echo '<head><title>Access Error</title></head>';
    echo '<body>';
    echo A::t('app', 'You are not authorized to view this page');
    echo '</body></html>';
    exit;
// Check Admins's privileges	
}elseif(CAuth::isLoggedInAsAdmin() && !Admins::hasPrivilege($privilege_category, array('add', 'edit'))){
    echo '<html>';
    echo '<head><title>Access Error</title></head>';
    echo '<body>';
    echo A::t('app', 'You have no privileges to view this page');
    echo '</body></html>';
    exit;
// Check Author's privileges	
}elseif(CAuth::isLoggedInAs('author') && !Modules::model()->isInstalled('mblog')){
    echo '<html>';
    echo '<head><title>Access Error</title></head>';
    echo '<body>';
    echo A::t('app', 'You have no privileges to view this page');
    echo '</body></html>';
    exit;
}else{    
    $loggedId = CAuth::getLoggedId();
	$loggedRole = CAuth::getLoggedRole();
	
    /** 
     * Check image directory and create it if needed
     */
	$hash = CConfig::get('installationKey') ? CConfig::get('installationKey') : 'admins_';
	$imageDir = 'images/upload/'.md5($hash.'-'.$loggedRole.'-'.$loggedId).'/';
    if(!$isDemo){
        if(!is_dir($baseDir.$imageDir)){
            if(!mkdir($baseDir.$imageDir, 0755, true)){
                A::t('core', 'Failed to create directory {path}.', array('{path}'=>$imageDir));
                exit;
            }
        }        
    }

    $imagebasedir = $baseDir.$imageDir;
    
    /**
     * Define the extentions you want to show within the directory listing.
     * The extensions also limit the files the user can upload to your image folders.
     */
    $supportedextentions = array('gif', 'png', 'jpeg', 'jpg', 'bmp');
    $filetypes = array ('png' => 'jpg.gif', 'jpeg' => 'jpg.gif', 'bmp' => 'jpg.gif', 'jpg' => 'jpg.gif', 'gif' => 'gif.gif', 'psd' => 'psd.gif');
    $act = !empty($_GET['act']) ? filter_var($_GET['act'], FILTER_SANITIZE_STRING) : '';
    $selFile = !empty($_GET['file']) ? $imageDir.str_replace(array('/', '\\', '..', ':', '\0', '%00'), '', $_GET['file']) : '';
    $msg = '';
    $getSort = 'date';
    $getOrder = 'desc';
    $files = array();
    
    if(isset($_FILES['image'])){
        if(is_uploaded_file($_FILES['image']['tmp_name'])){
            //@todo change base_dir!
            
            //@todo change image location and naming (if needed)
            $image = $imageDir.$_FILES['image']['name'];
            $ext = strtolower(substr($_FILES['image']['name'], strrpos($_FILES['image']['name'], '.')+1));
            $size = isset($_FILES['image']['size']) ? $_FILES['image']['size'] : 0;
    
            // prepare and check maximum allowed file size
            $ini_file_size = trim(ini_get('upload_max_filesize'));        
            $upload_file_size = ((int)$ini_file_size < (int)'10M') ? $ini_file_size : '10M';
            $last = strtolower($upload_file_size{strlen($upload_file_size)-1});
            switch($last) {
                case 'g':
                    $max_file_size = $upload_file_size * 1024*1024*1024; break;
                case 'm':
                    $max_file_size = $upload_file_size * 1024*1024; break;
                default:
                    $max_file_size = $upload_file_size; break;
            }
            
            if(in_array($ext, $supportedextentions)) $act = 'upload';
            if($size > $max_file_size) $act = 'wrong_size';
            
            if($act == 'upload'){
                if($isDemo){
                    $msg = 'This operation is blocked in demo version!';
                    $act = '';
                }else{
                    move_uploaded_file($_FILES['image']['tmp_name'], $baseDir.$image);
                }
            }elseif($act == 'wrong_size'){
                $msg = 'The uploaded file exceeds the maximum allowed size '.$upload_file_size.'!';            
            }else{
                $msg = 'Extension is not allowed!<br>Please select another image for uploading.';
            }
        }else{
            $msg = 'No file selected for uploading!';
        }
    }elseif($act == 'remove'){
        if($isDemo){
            $msg = 'This operation is blocked in demo version!';
            $act = '';
        }else{
            CFile::deleteFile($baseDir.$selFile);
        }
    }
    
    clearstatcache();
    $leadon = '';
    if($handle = opendir($baseDir.$imageDir)){
        $n=0;
        while(false !== ($file = readdir($handle))){ 
            //first see if this file is required in the listing
            if($file == '.' || $file == '..')  continue;
            
            if(@filetype($leadon.$file) != 'dir') {
                $n++;
                if($getSort == 'date'){
                    $key = @filemtime($leadon.$file).'.'.$n;
                }elseif($getSort == 'size'){
                    $key = @filesize($leadon.$file).'.'.$n;
                }else{
                    $key = $n;
                }
                $files[$key] = $file;
            }
        }
        closedir($handle); 
    }
    
    // Sort our files
    if($getSort == 'date'){
        @ksort($dirs, SORT_NUMERIC);
        @ksort($files, SORT_NUMERIC);
    }elseif($getSort == 'size'){
        @natcasesort($dirs); 
        @ksort($files, SORT_NUMERIC);
    }else{
        @natcasesort($dirs); 
        @natcasesort($files);
    }
    
    // Order correctly
    if($getOrder == 'desc'){
		$dirs = @array_reverse($dirs); 
		$files = @array_reverse($files);
	}
    $dirs = @array_values($dirs);
    $files = @array_values($files);
    
    $baseUrl = str_replace('assets/vendors/tinymce/plugins/imageupload/', '', A::app()->getRequest()->getBaseUrl());
}
    
?>
<!DOCTYPE HTML>	
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Upload/Select Image</title>
    <script type="text/javascript" src="../../tiny_mce_popup.js"></script>    
    <script type="text/javascript">
    function selectImage(img_src){
        var imgsrc = '<?= $baseUrl; ?>'+img_src;
        var ImageDialog = {
          init : function(ed) {
            ed.execCommand('mceInsertContent', false, 
                tinyMCEPopup.editor.dom.createHTML('img', {
                    src : imgsrc
                })
            );            
            tinyMCEPopup.editor.execCommand('mceRepaint');
            tinyMCEPopup.editor.focus();
            tinyMCEPopup.close();
          }
        };
        tinyMCEPopup.onInit.add(ImageDialog.init, ImageDialog);        
    }
    function deleteImage(file_name){
        window.location.href = 'upload.php?act=remove&file=' + file_name;
    }
    </script>
</head>
<body style="padding:5px;margin:0px;">
<?php if($act == 'upload'){ ?>
    <script type="text/javascript">selectImage('<?= $image; ?>');</script>
<?php }elseif($act == 'select'){ ?>
    <script type="text/javascript">selectImage('<?= $selFile; ?>');</script>
<?php }else{ ?>
   
    <form name="iform" style="margin:0px 0px 5px 0px" action="" method="post" enctype="multipart/form-data">
        <div style="display:inline-block;">
        <input id="file" style="float:left;" accept="image/*" type="file" name="image" />
        <button id="insert" <?= (($isDemo) ? "disabled='disabled'" : "");?>>Upload</button>
        </div>
    </form>    
    <fieldset>
    <legend>IMAGES:</legend>
    <?= ($msg) ? '<br>&nbsp;&nbsp;&nbsp;'.$msg : ''; ?>    
<?php

    $arsize = count($files);
    echo '<div style="overflow-x:auto;height:170px;">';
    echo '<table width="99%" border="0">';
    for($i=0; $i<$arsize; $i++) {
        $icon = 'unknown.png';
        $ext = strtolower(substr($files[$i], strrpos($files[$i], '.')+1));
        if(in_array($ext, $supportedextentions)){            
            $icon = ($filetypes[$ext]) ? $filetypes[$ext] : 'unknown.png';                        
            $filename = $files[$i];
            if(strlen($filename) > 43) {
                $filename = substr($files[$i], 0, 40) . '...';
            }
            $fileurl = $leadon . $files[$i];
            $filedir = str_replace($imagebasedir, '', $leadon);

            echo '<tr>';
            echo '<td width="30px" align="center"><img src="img/'.$icon.'" alt="icon" /></td>';
            echo '<td><a href="javascript:void(0)" onclick="window.location.href=\'upload.php?act=select&file='.$filedir.$filename.'\'"><b>'.$filename.'</b></a></td>';
            if($isDemo){
                echo '<td>[x]</td>';
            }else{
                echo '<td><a href="javascript:void(0)" onclick="if(confirm(\'Are you sure?\')) deleteImage(\''.$filename.'\');" title="Delete"><b>[x]</b></a></td>';
            }
            echo '</tr>';
        }
    }
    if(!$msg && !$arsize) echo '<tr><td colspan="3" align="center">No images found</td></tr>';
    echo '</table>';
    echo '</div>';
    echo '</fieldset>';
    
}
?>
</body>
</html>