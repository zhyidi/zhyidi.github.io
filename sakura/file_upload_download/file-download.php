<?php
$filename = $_POST['filename'];
if($filename=='' || $filename==NULL){
    // echo "<script> alert('请填写要下载的文件!'); </script>";
    echo "请填写要下载的文件!";
    exit();
}
$absolute_path = '/home/wwwroot/wordpress/wp-content/uploads/file-uploads/'.$filename;
if (file_exists($absolute_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($filename).'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($absolute_path));
    ob_clean();
    flush();
    readfile($absolute_path);
    exit;
} else {
    die("文件不存在！");
}
?>
