<?php

use Simp\Uploader\upload_system\SimpleWrapper;
use Simp\StreamWrapper\WrapperRegister\WrapperRegister;
use Simp\Uploader\upload_system\example_uploader\Form;

require_once __DIR__ . '/vendor/autoload.php';

WrapperRegister::register('simple', SimpleWrapper::class);

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $form_uploader = new Form;

    $form_uploader->addAllowedExtension('application/pdf');
    $form_uploader->addAllowedExtension('image/*');
    $form_uploader->addFileObject($_FILES['image']);

    $form_uploader->validate();

    $file = $form_uploader->getParseFilename();
    $form_uploader->moveFileUpload('simple://'.$file);
    dump($form_uploader);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="image" id="image">
        <input type="submit" value="Submit">
    </form>
</body>
</html>