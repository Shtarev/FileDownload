<?php
if(isset($_FILES['file'])) {
	if ($_FILES['file']['name'] !== '' && $_FILES['file']['error'] == 0) {
		$fileTmpName = $_FILES['file']['tmp_name'];
		$name = $_FILES['file']['name'];
		$extension = Name_extension::extension($name);
		$filesize = FileSizeConvert(filesize($fileTmpName));
		$fi = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($fi, $fileTmpName);
		$type = mb_strstr($mime, '/', true);
		$result = strpos($mime, 'image');
		if($result === 0) {
			$view = $name;
		}
		else {
			$view = 'example/example.jpg';
		}
		// загружаем файл
		if (move_uploaded_file($fileTmpName, __DIR__ . '/upload/'.$name)) {
			$data = [
				'definition' => true,
				'info' => 'File name: '.$name.' | File Type: '.$type.' | File Size: '.$filesize,
				'type' => $type,
				'size' => $filesize,
				'view' => $view,
				'file' => $name
			];
		}
		else {
			$data = [
				'definition' => false,
				'info' => 'Error occurred on the server when uploading the image!',
				'type' => '',
				'size' => '',
				'view' => '',
				'file' => ''
			];
		}
		echo json_encode($data);
	}
}

if(isset($_POST['del'])) {
	$res = unlink(__DIR__ . '/upload/'.$_POST['del']);
	if($res) {
		echo json_encode('Deleted');
	}
	else {
		echo json_encode('NO deleted');
	}
}

class Name_extension    
{    
	public static function name($file) {    
		return substr($file, 0, strrpos($file, '.'));    
	}    
	public static function extension($file) {    
		return substr($file, strrpos($file, '.')+1);    
	}    
	public static function random($file, $new_name = false) {    
		if($new_name === false) {    
			return uniqid().'.'.self::extension($file);    
		}    
		else {    
			return $new_name.'.'.self::extension($file);    
		}    
			
	}    
}

function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}