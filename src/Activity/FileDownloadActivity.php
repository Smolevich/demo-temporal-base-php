<?php
namespace App\Activity;

use Temporal\Activity\ActivityInterface;
use Temporal\Activity\ActivityMethod;

#[ActivityInterface]
class FileDownloadActivity
{
    #[ActivityMethod]
    public function downloadFile(string $url, string $destinationPath): string
    {
        $tempPath = $destinationPath . ".part";
        $startByte = file_exists($tempPath) ? filesize($tempPath) : 0;

        $fp = fopen($tempPath, "ab");
        if (!$fp) {
            throw new \Exception("Cannot open file: $tempPath");
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_BUFFERSIZE, 256 * 1024);
        curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($ch, $data) use ($fp) {
            fwrite($fp, $data);
            return strlen($data);
        });

        if ($startByte > 0) {
            curl_setopt($ch, CURLOPT_RANGE, "$startByte-");
        }

        curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception("Download error: " . curl_error($ch));
        }

        fclose($fp);
        curl_close($ch);

        rename($tempPath, $destinationPath);
        return "Downloaded: $destinationPath";
    }
}
