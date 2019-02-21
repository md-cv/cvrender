<?php
/**
 * 导出PDF
 */

require '../vendor/autoload.php';
require '../app/request-limit.php';
startLimit();

function getHTML($mdContent){
    $styleFileData = file_get_contents('../github.css');
    $style = "<style>{$styleFileData}</style>";
    $content = (new Parsedown())->text($mdContent);
    return $style.$content;
}
function render($fileName, $mdContent){
    $mpdf = new \Mpdf\Mpdf(['tempDir'=> sys_get_temp_dir()]);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->autoLangToFont = true;
    $mpdf->autoScriptToLang = true;
    $mpdf->WriteHTML(getHTML($mdContent));
    $mpdf->Output($fileName .'.pdf',\Mpdf\Output\Destination::DOWNLOAD);
}


$mdContent = isset($_POST['text']) ? $_POST['text'] : '';
$name =  isset($_POST['name']) ? $_POST['name'] : '简历';

try{
    render($name, $mdContent);
} catch (Error $err){
    print_r($err);
}


