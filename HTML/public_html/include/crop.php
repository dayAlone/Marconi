<?
    if(AcImage::isFileExists($_SERVER['DOCUMENT_ROOT'].$_REQUEST['img'])) {
        $img = AcImage::createImage($_SERVER['DOCUMENT_ROOT'].$_REQUEST['img']);
        $img->resize(1200, 630);
        AcImage::setBackgroundColor(255, 255, 255);
    }

?>
