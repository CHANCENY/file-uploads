<?php

namespace Simp\Uploader\upload_system\uploader\traits;

trait Properties 
{
    
    protected string $parse_filename;

    protected int $parse_filesize;

    protected string $file_type;

    protected string $system_filename;

    protected array $allowed_extenstions;

    protected int $allowed_max_size;

    protected array $temp_object = [];

    protected array $file_object = [];

    protected bool $validated = false;
}