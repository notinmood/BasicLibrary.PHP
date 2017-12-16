<?php
namespace Hiland\Utils\IO\File\FileUtilDriver;
class Sae
{
    private $sae;

    public function __construct()
    {
        $this->sae = new \SaeStorage();
    }

    public function moveFile($fileUrl, $aimUrl, $overWrite = true)
    {
        $domain = $this->getDomain($fileUrl);
        $path = $this->getPath($fileUrl);
        if (!$this->sae->fileExists($domain, $path)) {
            return false;
        }
        $content = $this->sae->read($domain, $path);
        if (!$content) {
            return false;
        }
        $this->sae->delete($domain, $path);

        $domain = $this->getDomain($aimUrl);
        $path = $this->getPath($aimUrl);
        if ($path == '') {
            return false;
        }
        if ($this->sae->fileExists($domain, $path) && $overWrite == false) {
            return false;
        }
        if ($this->sae->fileExists($domain, $path) && $overWrite == true) {
            $this->sae->delete($domain, $path);
        }
        $result = $this->sae->write($domain, $path, $content);
        return $result != false ? true : false;
    }

    private function getDomain($url)
    {
        $url = trim($url, './');
        if (preg_match('|^http://\S+sinaapp\.com|iU', $url, $feild)) {
            preg_match('|-\S+\.|iU', $feild[0], $match);
            $domain = trim($match[0], "-.");
        } else {
            $sp = strpos($url, '/');
            if ($sp) {
                $domain = substr($url, 0, $sp);
            } else {
                $domain = $url;
            }
        }
        return strtolower($domain);
    }

    private function getPath($url)
    {
        $url = trim($url, './');
        if (preg_match('|^http://\S+sinaapp\.com|iU', $url)) {
            $path = preg_replace('|^http://\S+sinaapp\.com|iU', '', $url);
            $path = trim($path, "/");
        } else {
            $sp = strpos($url, '/');
            if ($sp) {
                $path = substr($url, $sp + 1);
            } else {
                $path = '';
            }
        }
        return $path;
    }

    public function clearDir($dirUrl)
    {
        $placeholderUrl = trim($dirUrl, '/') . '/placeholder.txt';
        if (!$this->fileExists($placeholderUrl)) {
            $this->writeFile($placeholderUrl, 'This is a placeholder.');
        }
        $infos = $this->getList($dirUrl);
        $result = true;
        foreach ($infos['dirs'] as $dir) {
            $result = $this->unlinkDir($dir['fullName']);
        }
        foreach ($infos['files'] as $file) {
            if ($file['Name'] == 'placeholder.txt') {
                continue;
            }
            $result = $this->unlinkFile($file['fullName']);
        }
        return $result;
    }

    public function fileExists($fileUrl)
    {
        $domain = $this->getDomain($fileUrl);
        $path = $this->getPath($fileUrl);
        return $this->sae->fileExists($domain, $path);
    }

    public function writeFile($fileUrl, $content)
    {
        $domain = $this->getDomain($fileUrl);
        $path = $this->getPath($fileUrl);
        if ($this->sae->fileExists($domain, $path)) {
            $this->sae->delete($domain, $path);
        }
        return $this->sae->write($domain, $path, $content);
    }

    public function getList($dirUrl)
    {
        $domain = $this->getDomain($dirUrl);
        $path = $this->getPath($dirUrl);
        if ($path != '') {
            $result = $this->sae->getListByPath($domain, $path);
        } else {
            $result = $this->sae->getListByPath($domain);
        }
        for ($i = 0; $i < count($result['dirs']); $i++) {
            $fullPath = trim($result['dirs'][$i]['fullName'], '/');
            $result['dirs'][$i]['fullName'] = $this->sae->getUrl($domain, $fullPath);
        }
        for ($k = 0; $k < count($result['files']); $k++) {
            $fullPath = trim($result['files'][$k]['fullName'], '/');
            $result['files'][$k]['fullName'] = $this->sae->getUrl($domain, $fullPath);
        }
        return $result;
    }

    public function unlinkDir($dirUrl)
    {
        $infos = $this->getList($dirUrl);
        $result = true;
        foreach ($infos['dirs'] as $dir) {
            $result = $this->unlinkDir($dir['fullName']);
        }
        foreach ($infos['files'] as $file) {
            $result = $this->unlinkFile($file['fullName']);
        }
        return $result;
    }

    public function unlinkFile($aimUrl)
    {
        $domain = $this->getDomain($aimUrl);
        $path = $this->getPath($aimUrl);
        if (!$this->sae->fileExists($domain, $path)) {
            return false;
        }
        return $this->sae->delete($domain, $path);
    }

    public function readFile($fileUrl)
    {
        $domain = $this->getDomain($fileUrl);
        $path = $this->getPath($fileUrl);
        if (!$this->sae->fileExists($domain, $path)) {
            return false;
        }
        return $this->sae->read($domain, $path);
    }

    public function encodeUrl($url)
    {
        $domain = $this->getDomain($url);
        $path = $this->getPath($url);
        return $this->sae->getUrl($domain, $path);
    }

    public function decodeUrl($url)
    {
        $domain = $this->getDomain($url);
        $path = $this->getPath($url);
        return $domain . '/' . $path;
    }
}