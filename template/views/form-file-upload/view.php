<?php

require_once (DATA_RESOURCE_PATH . "/Product.php");
require_once (LIBRARY_PATH . "/form/FileUploader.php");
require_once (LIBRARY_PATH . "/form/FormHandler.php");


if (isset($_POST['img']))
    print_r($_FILES);


// UPDATE INFO
try {

    $file = new FileUploader(new FileManager());
    $file->setFilePath(UPLOADED_FILES_PATH)
        ->setFileName("some_name")
        ->setFileMaxSizeInBytes(32)
        ->setAcceptedFileExtensions(["jpg", "jpeg"]);

    $fileUploadForm = new FormHandler($file);
    $fileUploadForm->navigateTo("form-handler")
        ->setEntity("File")
        ->setVerb("add")
        ->setParameter(12334)
        ->setNavigateAfterUpdate(true)
        ->setPathSuccess("form-file-upload")
        ->setPathFail("home");
} catch (Exception $e) {
}

?>
    <div align="center">
        <h4 >Form Products View</h4>

        <?php
        try {
            $fileUploadForm->printFileUploadForm();
        } catch (Exception $e) {
        }
        ?>

            <input type="submit" value="Upload">
        </form>

        <br>
        <br>
        <table style="width:50%">
            <tr>
                <td>File #</td>
                <td>Name</td>
                <td>Temp File</td>
                <td>Type</td>
                <td>Size</td>
            </tr>

        <?php
        if (isset($_FILES['files'])) {

            $myFile = $_FILES['files'];

            $fileCount = count($myFile["name"]);

            for ($i = 0; $i < $fileCount; $i++) {
                ?>

                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= $myFile["name"][$i] ?></td>
                    <td><?= $myFile["tmp_name"][$i] ?></td>
                    <td><?= $myFile["type"][$i] ?></td>
                    <td><?= $myFile["error"][$i] ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </div>