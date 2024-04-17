<?php
    $destination='/home/wwwroot/wordpress/wp-content/uploads/file-uploads/'.$_FILES['myfile']['name'];
    if (file_exists($destination)) {
        echo "文件名称已经存在,文件上传失败!";
        exit();
    }
    if ($_FILES['myFile']['error'] == UPLOAD_ERR_OK) {
        if(is_uploaded_file($_FILES['myfile']['tmp_name'])){
            if(move_uploaded_file($_FILES['myfile']['tmp_name'],$destination)){
                echo "文件上传成功!" . "<br>";
                echo "文件名称: " . $_FILES["myfile"]["name"] . "<br>";
                echo "文件类型: " . $_FILES["myfile"]["type"] . "<br>";
                echo "文件大小: " . ($_FILES["myfile"]["size"] / 1024) . " kB<br>";
            }
        }
    } else {
        echo "上传错误: " .$_FILES["file"]["error"];
    }
    
?>
