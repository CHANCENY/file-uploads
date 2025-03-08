<?php

/**
 * 
 * Uploader to be used to handle url upload.
 * 
 */

namespace Simp\Uploader\upload_system\uploader\UrlUploader;

use Simp\Uploader\upload_system\uploader\traits\Properties;
use Simp\Uploader\upload_system\uploader\traits\CommonFunction;


abstract class UrlUploader
{

    protected ?string $original_url = null;

    use Properties;

    use CommonFunction;

    public function __construct()
    {
        $this->parse_filename = '';
        $this->parse_filesize = 0;
        $this->file_type = '';
        $this->system_filename = '';
        $this->allowed_extenstions = [];
        $this->allowed_max_size = 500000;
    }

    public function addUrl(string $url): self
    {
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $this->original_url = $url;
        }
        return $this;
    }

    public function validate(): self
    {
        if (!empty($this->original_url)) {
            // make the curl request to get all headers. eg mime_type, size, filename if exist
            $curl = curl_init($this->original_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_NOBODY, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_HEADER, true);
            curl_exec($curl);
            $info = curl_getinfo($curl);
            curl_close($curl);

            $mime_type = $info['content_type'] ?? null;
            $size = $info['download_content_length'] ?? null;

            // validate the mimetype of the given url
            foreach ($this->allowed_extenstions as $allowed_extenstion) {
                $allowed_list = explode('/', $allowed_extenstion);
                $mime_list = explode('/', $mime_type);

                // both $allowed_list and $mime_list are of 2 element
                if (count($mime_list) === count($allowed_list)) {

                    // check for first part
                    if ($allowed_list[0] === $mime_list[0]) {
                        // Two options are need to be checked here if $allowed_list second part is *
                        if (end($allowed_list) === '*') {
                            $this->validated = true;
                        }

                        // has part need to be checked.
                        elseif (end($allowed_list) === end($mime_list)) {
                            $this->validated = true;
                        }
                    }
                }
            }

            // lets validated file size here only if extension is checked out.
            if (!$this->validated || $this->allowed_max_size < (int) $size) {
                $this->validated = false;
                return $this;
            }

            // At this point we can say that the validation is dane. the lets write temp file.
            $this->parse_filesize = $size;
            $this->file_type = $mime_type;

            $ext = pathinfo($this->original_url, PATHINFO_EXTENSION);

            if (empty($ext)) {
                $ext = $this->getExtensionFromMime($mime_type);
                if (empty($ext)) {
                    $list = explode('/', $mime_type);
                    $ext = end($list);
                }
            }


            $filename = pathinfo($this->original_url, PATHINFO_FILENAME);
            if (!empty($filename)) {
                $this->parse_filename = $filename . '.' . $ext;
            } else {
                $this->parse_filename = 'url-upload-' . uniqid() . '.' . $ext;
            }

            $this->temp_object['file_path'] = sys_get_temp_dir() . DIRECTORY_SEPARATOR . uniqid('url_uploader.');
            
            $curl = curl_init($this->original_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_NOBODY, false); // Ensure the body is fetched
            curl_setopt($curl, CURLOPT_HEADER, false); // Do not include headers in the response
            $response = curl_exec($curl);
            curl_close($curl);

            $written_size = file_put_contents($this->temp_object['file_path'], $response);
            if ($written_size !== false && (abs($written_size - $size) / 100 )<= 25) {
                $this->temp_object['size'] = $written_size;
                $this->temp_object['mime_type'] = $mime_type;
                $this->temp_object['extension'] = $ext;
                $this->temp_object['name'] = $this->parse_filename;
            }
        }

        return $this;
    }

  

    public function moveFileUpload(string $destination): self 
    {

        if (!$this->validated) {
            return $this;
        }
        $this->system_filename = $destination;
        $counter = 0;
        while(file_exists($this->system_filename)) {
            $only_name = explode('.', $destination);
            $this->system_filename = $only_name[0] ."_{$counter}.". end($only_name); 
            $counter += 1;
        }
        
        @touch($this->system_filename);
        $handler = fopen($this->system_filename, 'w+');
        $written_size = fwrite($handler, file_get_contents($this->temp_object['file_path']), $this->temp_object['size']);
        fclose($handler);
        if ($written_size !== false) {
            $this->file_object['file_path'] = $this->system_filename;
            $this->file_object = array_merge($this->temp_object, $this->file_object);
            $this->file_object['size'] = $written_size;
            $this->file_object['name'] = pathinfo($this->system_filename,PATHINFO_BASENAME);
            $this->file_object['file_path'] = $this->system_filename;
            @unlink($this->temp_object['file_path']);
            $this->temp_object = [];
        }
        return $this;
    }

    public function getOriginalUrl(): ?string
    {
        return $this->original_url;
    }

   
}
