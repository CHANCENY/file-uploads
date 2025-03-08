<?php

use Simp\Uploader\upload_system\SimpleWrapper;
use Simp\Uploader\upload_system\example_uploader\Url;
use Simp\StreamWrapper\WrapperRegister\WrapperRegister;


require_once __DIR__ . '/vendor/autoload.php';


WrapperRegister::register('simple', SimpleWrapper::class);

$url_uploader = new Url;

$url_uploader->addAllowedExtension('application/pdf');
$url_uploader->addAllowedExtension('image/*');

$url_uploader->addUrl('https://articlepulsehub.com/sites/default/files/public/26-October-2024/error_handling_jpg.jpeg');

$url_uploader->validate()->moveFileUpload('simple://image.jpeg');


dump($url_uploader);

