<?php

namespace Simp\Uploader\upload_system;

use Simp\StreamWrapper\Stream\GlobalStreamWrapper;

class SimpleWrapper extends GlobalStreamWrapper {

    protected string $base_path = "sites";
    protected string $stream_name = "simple";

}