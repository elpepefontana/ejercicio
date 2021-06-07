<?php

namespace System\Core;

class FileManager 
{

    public static $message;

    /******************************************** FUNCIONES DE ARBOL ************************************************** */

    public static function createTreeViewData($dir) 
    {
        $out  = self::createNode($dir);
        $json = json_encode($out);
        return $json;
    }

    public static function createNode($dir) 
    {
        if (!is_dir($dir)) {
            return false;
        }
        $out = array();
        $aList = scandir($dir);
        $i = 1;
        foreach ($aList as $list) {
            if ($list === '.' || $list === '..' || $list === '.DS_Store') {
                continue;
            }
            $prev = $dir . '/' . $list;
            if (is_dir($prev)) {
                $out[$i]['name'] = $list;
                $out[$i]['type'] = 'folder';
                $out[$i]['items'] = self::createNode($prev);
            } elseif (is_file($prev)) {
                $prev = $dir;
                $out[$i]['name'] = $list;
                $out[$i]['type'] = 'file';
            }
            $i++;
        }
        self::multiSortArray($out, 'SORT_DESC');
        return is_array($out) && count($out) > 0 ? $out : false;
    }
    
    public static function multiSortArray($array, $orderType) 
    {
        $columns_1 = array_column($array, 'type');
        $columns_2 = array_column($array, 'name');
        if ($orderType === 'SORT_DESC') 
            return array_multisort($columns_1, SORT_DESC, $columns_2, SORT_ASC, $array);
        else
            return array_multisort($columns_1, SORT_ASC, $columns_2, SORT_ASC, $array);
    }

    public static function createJsonFile($basePath, $dirName, $fileName, $data = Array()) 
    {
        $fp = fopen($basePath . $dirName . $fileName, 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
    }

    /******************************************** FUNCIONES DE CARPETAS Y ARCHIVOS ************************************************** */

    // FUNCIONES DE DIRECTORIOS

    public static function createDir($path) 
    {
        if (is_dir($path)) { 
            return true; 
        }
        return mkdir($path, 0777, true) ? true : false;
    }

    public static function deleteDir($basePath, $dirName) 
    {
        if (!is_dir($basePath . $dirName)) { 
            return true; 
        }
        return rmdir($basePath . $dirName);
    }

    public static function listDirs(string $dir): array 
    {
        if (!is_dir($dir)) { 
            return array(); 
        }
        $aList = scandir($dir);
        foreach ($aList as $list) {
            if (is_dir($dir . $list) && $list !== '.' && $list !== '..') {
                $out[] = $list;
            }
        }
        return $out;
    }

    public static function setDirsArray(): array 
    {
        $list = self::listDirs();
        foreach ($list as $val) {
            $out[] = array($val, $val);
        }
        return $out;
    }

    public static function listFilesInDir($dir) 
    {
        if (!is_dir($dir)) { 
            return false; 
        }
        $aList = scandir($dir);
        foreach ($aList as $file) {
            if (!is_file($dir . $file) || $file === '.DS_Store') {
                continue;
            }
            $out[] = $file;
        }
        return $out;
    }
    
    public static function searchFileInDir($dir, $searchedFile)
    {
        if (!is_dir($dir)) { 
            return false; 
        }
        return is_file($dir . $searchedFile);
    }
    
    public static function searchFileInDirAndSudDirAndReturnFile($dir, $searchedFile)
    {
        if (is_file($dir . $searchedFile . '.php')) {
            return $dir . $searchedFile . '.php';
        }
        
        $aDir = self::listDirs($dir);
        if (!is_array($aDir) || count($aDir) === 0) {
            return $dir . '/' . $searchedFile . '.php';
        }
        
        foreach ($aDir as $item) {
            $path = $dir . $item . '/' . $searchedFile . '.php';
            if (!is_file($path)) {
                continue;
            }
            return $path;
        }
    }
    
    public static function listFilesWithSpecifficExtensionInDir($dir, $extension = '.php') 
    {
        if (!is_dir($dir)) { 
            return false; 
        }
        $aList = scandir($dir);
        foreach ($aList as $file) {
            if (strpos($file, $extension) === false) {
                continue;
            }
            $out[] = $dir . $file;
        }
        return $out;
    }

    // FUNCIONES DE CREACION DE ARCHIVOS

    public static function createFile($name, $content, $path, $create, $ext = 'php') 
    {
        $fileName = $path . $name . '.' . $ext;
        if (file_exists($fileName) && !$create) {
            return "\tArchivo \"{$name}\" ya existente. No se ha forzado su sobreescritura.\n";
        }
        $file = fopen($fileName, 'w');
        if (!$file) {
            return "\tError: Archivo \"{$name}\" NO creado.\n";
        }
        fwrite($file, $content);
        flock($file, LOCK_UN);
        fclose($file);
        return "\tArchivo \"{$name}\" creado.\n";
    }

    public static function createPath($path) 
    {
        if (empty($path) || !strpos($path, '/')) {
            return "\tDirectorio \"{$path}\" NO creado. Error Path mal definido.\n";
        }
        $aPath  = explode('/', $path);
        $flag   = true;
        $toPath = '';
        for ($i = 0; $i < count($aPath); $i++) {
            $toPath .= ($i === 0) ? $aPath[$i] : "/" . $aPath[$i];
            if ($i > 2){
                $flag = $flag ? self::createDir($toPath) : false;
            }
        }
        return is_dir($path) ? "Directorio \"{$path}\" creado.\n" : "Error: Directorio \"{$path}\" no creado.\n";            
    }

    public static function deleteFile($fileNameAndPath) 
    {
        if (!file_exists($fileNameAndPath)) { 
            return true; 
        }
        $file = fopen($fileNameAndPath, 'r+');
        flock($file, LOCK_UN);
        fclose($file);
        return chmod($fileNameAndPath, 0777) ? unlink($fileNameAndPath) : false;
    }

    // FUNCIONES DE CREACION DE DIRECTORIOS Y ARCHIVOS COMBINADAS

    public static function createDirAndFile($basePath, $dirName, $name, $content, $create) 
    {
        if (self::createDir($basePath . $dirName)) {
            return self::createFile($name, $content, $basePath . $dirName, $create);
        }
        return false;
    }

    public static function deleteDirAndFile($basePath, $dirName, $name, $content, $path, $create) 
    {
        if (!is_dir($basePath . $dirName)) {
            return true;
        }
        foreach (scandir($basePath . $dirName) as $file) {
            self::deleteFile($basePath . $dirName . $file);
        }
        return count(scandir($basePath . $dirName)) == 0 ? rmdir($basePath . $dirName) : false;
    }

    public static function createPathAndFile($name, $content, $path, $create) 
    {
        if (self::createPath($path)) {
            return self::createFile($name, $content, $path, $create);
        }
        return false;
    }

    // FUNCIONES DE CONTENIDO DE ARCHIVOS
    
    public static function apendContentToFile(string $path, string $fileName, string $newContent): bool 
    {
        $content = file_get_contents($path . $fileName);
        if (strpos($content, $newContent)) {
            return true;
        }
        return file_put_contents($path . $fileName, $newContent, FILE_APPEND | LOCK_EX) !== false ? true : false;
    }
    
    public static function removeFileContent(string $path, string $fileName, string $remove): bool 
    {
        $lines = file($path . $fileName, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $key => $line) {
            if (strpos($line, $remove) === false) {
                continue;
            }
            unset($lines[$key]);
        }
        $data = implode(PHP_EOL, $lines);
        return !file_put_contents($path . $fileName, $data) ? false : true;
    }
    
    public static function searchFileContent(string $path, string $fileName, string $search)
    {
        if (!is_file($path . $fileName . ".php")) { 
            return false; 
        }
        $fileContent = file_get_contents($path . $fileName);
        return !strpos($fileContent, $search) ? false : true;
    }
    
    public static function replaceFileContent(string $path, string $fileName, string $toReplace, string $replaceWith): string 
    {
        if (!is_file($path . $fileName)) { 
            return false; 
        }
        file_put_contents($path . $fileName, str_replace($toReplace, $replaceWith, file_get_contents($path . $fileName)));
    }
    
    // READ JSON FILE
    
    public static function readJsonFile(string $path, string $fileName, bool $bToArray = true) 
    {
        if (!is_file($path . $fileName)) { 
            return false;
        }
        $json = file_get_contents($path . $fileName);
        return json_decode($json, $bToArray);
    }
    
    /******************************************** FUNCIONES DE IMAGEN ************************************************** */

    // ROTAR IMAGEN

    public static function imageFixOrientation($fileName, $orientation) 
    {
        $image = imagecreatefromjpeg($fileName);
        switch ($orientation) {
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
        }
        imagejpeg($image, $fileName, IMAGES_QUALITY);
        imagedestroy($image);
    }

    // REDIMENCIONAR IMAGEN

    public static function imageResize($fileName) 
    {
        list($width, $height) = getimagesize($fileName);
        $newSizes = self::setNewImageSizes($width, $height);
        $image = self::setImageResource($fileName);
        $dst = imagecreatetruecolor($newSizes['width'], $newSizes['height']);
        imagecopyresampled($dst, $image, 0, 0, 0, 0, $newSizes['width'], $newSizes['height'], $width, $height);
        if ($image && self::imageSharpen($dst) && self::imageContrast($dst)) {
            imagejpeg($dst, $fileName, IMAGES_QUALITY);
            imagedestroy($image);
        }
    }
    
    public static function setImageResource($pathAndFile)
    {
        $extension = self::getFileExtension($pathAndFile);
        $callFunction = 'imagecreatefrom' . $extension;
        return $callFunction($pathAndFile);
    }
    
    public static function getFileExtension($pathAndFile)
    {
        return pathinfo($pathAndFile, PATHINFO_EXTENSION);
    }

    public static function getImageRatio(int $width, int $height): array 
    {
        $ratio = $width / $height;
        if ($ratio > 1) {
            return array('ratio' => $ratio, 'type' => 'landscape');
        } elseif ($ratio == 1) {
            return array('ratio' => $ratio, 'type' => 'square');
        } else {
            return array('ratio' => $ratio, 'type' => 'portrait');
        }
    }

    public static function setNewImageSizes(int $width, int $height): array 
    {
        $ratio = self::getImageRatio($width, $height);
        if (IMAGES_WIDTH/IMAGES_HEIGHT > $ratio['ratio']) {
            $newWidth  = IMAGES_HEIGHT * $ratio['ratio'];
            $newHeight = IMAGES_HEIGHT;
        } else {
            $newWidth  = IMAGES_WIDTH;
            $newHeight = IMAGES_WIDTH / $ratio['ratio'];
        }
        return array(
            'width'  => $newWidth,
            'height' => $newHeight
        );
    }

    // MEJORAR CONTRASTE

    public static function imageContrast($image) 
    {
        return imagefilter($image, IMG_FILTER_CONTRAST, -3) ? true : false;
    }

    // ENFOCAR IMAGEN

    public static function imageSharpen($image) 
    {
        $sharpen = array([-1, -1, -1], [-1, 12, -1], [-1, -1, -1]);
        $divisor = array_sum(array_map('array_sum', $sharpen));
        return imageconvolution($image, $sharpen, $divisor, 0);
    }

    // EMBOSS IMAGEN

    public static function imageEmboss($image) 
    {
        $emboss = array([-2, -1, 0], [-1, 1, 1], [0, 1, 2]);
        $divisor = array_sum(array_map('array_sum', $emboss));
        return imageconvolution($image, $emboss, $divisor, 0);
    }

    public static function imageResizeImagick($fileName, $w, $h) 
    {
        if (!extension_loaded('imagick')) {
            return false;
        }
        $image = new imagick($fileName);
        $width  = $image->getimagewidth();
        $height = $image->getimageheight();
        $aNewSize  = self::setNewImageSizes($w, $h);
        $newWidth  = $aNewSize['width'];
        $newHeight = $aNewSize['height'];
        $image->resizeImage($newWidth, $newHeight, Imagick::FILTER_QUADRATIC, 1);
        $image->normalizeImage();
        $image->unsharpMaskImage(0, 0.5, 1, 0.05);
        $image->setImageFormat("jpg");
        $image->setCompressionQuality(IMAGES_QUALITY);
        $image->writeImage($fileName);
        $image->removeImage();
    }
    
    // MOSTRAR IMAGEN

    public static function createGdImage($imageName) 
    {
        ob_start();
        header("Content-type: image/jpeg");
        $image = imagecreatefromjpeg($imageName);
        imagejpeg($image, NULL, IMAGES_QUALITY);
        imagedestroy($image);
        $rawImageBytes = ob_get_clean();
        return base64_encode($rawImageBytes);
    }

    // SUBIR IMAGEN

    public static function generateNewFileName($client, $extension) 
    {
        return !empty($client) && !empty($extension) ? $client . '.' . date('YmdHis') . '.' . $extension : '';
    }

    /********************* FUNCIONES DE SUBIDA Y BAJADA DE ARCHIVOS ************************************************** */

    public static function saveUploadedFile($fileToUpload, $dirPathAndName) 
    {
        return move_uploaded_file($fileToUpload, $dirPathAndName) ? true : false;
    }
    
    public static function prepareAndUploadImages(string $originalFile, string $destenyFile): bool 
    {
        if (!self::saveUploadedFile($originalFile, $destenyFile)) {
            return false;
        }
        if (IMAGES_RESIZE) {
            self::imageResize($destenyFile);
        }
        return true;
    }

    public static function fileDownload($filePath) 
    {
        if (!file_exists($filePath)) {
            return false;
        }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        flush(); // Flush system output buffer
        return readfile($filepath);
    }

    /******************************************** FUNCIONES DE CSV ************************************************** */

    public static function csvToArray($csvFile) 
    {
        if (!$handle = fopen($csvFile, 'r')) {
            return [];
        }
        $header  = fgetcsv($handle, 5000, ';');
        while (($row = fgetcsv($handle, 5000, ';')) !== false) {
            foreach ($row as &$field) {
                $field = self::autoUTF($field);
                //$field = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $field);
                $field = str_replace(array("\r\n", "\r", "\n", ";"), "", $field);
                $field = str_replace(array("'"), array("\'"), $field);
            }
            if ($header) {
                $arrData[] = array_combine($header, $row);
            } else {
                $header = $row;
            }
        }
        fclose($handle);
    }

    public static function autoUTF($string) 
    {
        if (preg_match('#[\x80-\x{1FF}\x{2000}-\x{3FFF}]#u', $string))
            return $string;
        if (preg_match('#[\x7F-\x9F\xBC]#', $string))
            return iconv('WINDOWS-1250', 'UTF-8', $string);
        return iconv('ISO-8859-2', 'UTF-8', $string);
    }

    public static function getCsvHeader($csvFile) 
    {
        if (!is_file($csvFile)) {
            return false;
        }
        $file = fopen($csvFile, 'r');
        $out = fgetcsv($file, 5000, ';');
        fclose($file);
        return $out;
    }

    public static function createCsv($path, $fileName, $content) 
    {
        $fd = fopen($path . $fileName, "w");
        fputs($fd, $content);
        fclose($fd);
    }

    /******************************************** FUNCIONES DE FTP ************************************************** */

    public static function setFTPConnection($url, $user, $pass) 
    {
        $ftp = ftp_connect($url);
        if (!ftp_login($ftp, $user, $pass)) { 
            return false; 
        }
        return $ftp;
    }

    public static function getFileContent($url, $path, $user, $pass) 
    {
        $connection = setFTPConnection($url, $user, $pass);
        ftp_pasv($connection, true);
        $h = fopen('php://temp', 'r+');
        ftp_fget($connection, $h, $path, FTP_BINARY, 0);
        $fstats = fstat($h);
        fseek($h, 0);
        $contents = fread($h, $fstats['size']);
        fclose($h);
        ftp_close($connection);
        return $contents;
    }

}
