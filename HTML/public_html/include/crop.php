<?
    require($_SERVER['DOCUMENT_ROOT'].'/include/images/AcImage.php');
    error_reporting(0);
    if(AcImage::isFileExists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['img'])) {
        $img = AcImage::createImage($_SERVER['DOCUMENT_ROOT'].$_REQUEST['img']);
        if (isset($_REQUEST['vk'])) {
            $img->resize(1000, 700);
        } else {
            $img->resize(1200, 630);
        }
        //header("Content-Length: 0");
        //imagejpeg($img->getResource());
        header('Content-Type: image/jpeg');
        $img->save(NULL);
    }

?>
