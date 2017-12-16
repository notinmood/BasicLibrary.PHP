<?php
namespace Hiland\Utils\IO\File\FileUtilDriver;
class File
{
    public function moveFile($fileUrl, $aimUrl, $overWrite = true)
    {
        if (!is_file($fileUrl)) {
            return false;
        }
        if (is_file($aimUrl) && $overWrite == false) {
            return false;
        } elseif (is_file($aimUrl) && $overWrite == true) {
            $this->unlinkFile($aimUrl);
        }
        $aimDir = dirname($aimUrl);
        $this->createDir($aimDir);
        rename($fileUrl, $aimUrl);
        return true;
    }

    public function unlinkFile($aimUrl)
    {
        if (is_file($aimUrl)) {
            unlink($aimUrl);
            return true;
        } else {
            return false;
        }
    }

    private function createDir($aimUrl)
    {
        $aimDir = '';
        $arr = explode('/', $aimUrl);
        $result = true;
        foreach ($arr as $str) {
            $aimDir .= $str . '/';
            if (!is_dir($aimDir)) {
                $result = mkdir($aimDir);
            }
        }
        return $result;
    }

    public function clearDir($dirUrl)
    {
        $dirUrl = rtrim($dirUrl, '/');
        if (!is_dir($dirUrl)) {
            return false;
        }
        $infos = $this->getList($dirUrl);
        $result = true;
        foreach ($infos['files'] as $file) {
            $result = $this->unlinkFile($file['fullName']);
        }
        foreach ($infos['dirs'] as $dir) {
            $result = $this->unlinkDir($dir['fullName']);
        }
        return $result;
    }

    public function getList($dirUrl)
    {
        $dirUrl = rtrim($dirUrl, '/');
        if (!is_dir($dirUrl)) {
            return false;
        }
        $fileList = array();
        $dirList = array();
        $objects = scandir($dirUrl);
        foreach ($objects as $obj) {
            if ($obj == '.' || $obj == '..') {
                continue;
            }
            $fileUrl = $dirUrl . '/' . $obj;
            if (is_file($fileUrl)) {
                $filesize = filesize($fileUrl);
                $fileupdate = fileatime($fileUrl);
                array_push($fileList, array('Name' => $obj, 'fullName' => $fileUrl, 'length' => $filesize, 'uploadTime' => $fileupdate));
            }
            if (is_dir($fileUrl)) {
                array_push($dirList, array('name' => $obj, 'fullName' => $fileUrl));
            }
        }
        return array('dirNum' => count($dirList), 'fileNum' => count($fileList), 'dirs' => $dirList, 'files' => $fileList);
    }

    public function unlinkDir($dirUrl)
    {
        $dirUrl = rtrim($dirUrl, '/');
        if (!is_dir($dirUrl)) {
            return false;
        }
        $infos = $this->getList($dirUrl);
        foreach ($infos['files'] as $file) {
            $this->unlinkFile($file['fullName']);
        }
        foreach ($infos['dirs'] as $dir) {
            $this->unlinkDir($dir['fullName']);
        }
        return rmdir($dirUrl);
    }

    public function readFile($fileUrl)
    {
        if (!is_file($fileUrl)) {
            return false;
        }
        return file_get_contents($fileUrl);
    }

    public function writeFile($fileUrl, $content)
    {
        $fileDir = dirname($fileUrl);
        $this->createDir($fileDir);
        if (file_put_contents($fileUrl, $content) === false) {
            return false;
        } else {
            return $fileUrl;
        }
    }

    public function encodeUrl($url)
    {
        return $url;
    }

    public function decodeUrl($url)
    {
        return $url;
    }

    public function fileExists($fileUrl)
    {
        return is_file($fileUrl);
    }
}