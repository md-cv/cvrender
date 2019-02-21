<?php
/**
 * 导出PDF
 */

require '../vendor/autoload.php';
require '../app/request-limit.php';
startLimit();
function getStyle(){
    return file_get_contents('../github.css');
}
function getHTML($mdContent){
    $html =  (new Parsedown())->text($mdContent);
//    return $html;
    return '<div class="markdown-body">'.$html.'</div>';
}
function render($fileName, $mdContent){
    $defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
    $fontDirs = $defaultConfig['fontDir'];

    $defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
    $fontData = $defaultFontConfig['fontdata'];

    $option = [
        'tempDir'=> sys_get_temp_dir(),
        'fontDir' => array_merge($fontDirs, [
            dirname(dirname(__FILE__)). '/fonts',
        ]),
        'fontdata' => $fontData + [
                'simsun' => [
                    'R' => 'simsun.ttf',
                    'I' => 'simsun.ttf',
                    'B'=>'simsun.bold.ttf',
                 ]
            ],
        'default_font' => 'simsun'
    ];

    $mpdf = new \Mpdf\Mpdf($option);
    $mpdf->SetDisplayMode('fullpage');
    $mpdf->useAdobeCJK = true;
    $mpdf->autoVietnamese = true;
    $mpdf->autoLangToFont = true;
    $mpdf->autoScriptToLang = true;
    $mpdf->autoArabic = true;
    $mpdf->ignore_invalid_utf8 = true;
    $mpdf->ignorefollowingspaces = true;
    $mpdf->WriteHTML(getStyle(),\Mpdf\HTMLParserMode::HEADER_CSS);
    $mpdf->WriteHTML(getHTML($mdContent),\Mpdf\HTMLParserMode::HTML_BODY);
    $mpdf->Output($fileName .'.pdf',\Mpdf\Output\Destination::DOWNLOAD);
}


$mdContent = isset($_POST['text']) ? $_POST['text'] : '';
$name =  isset($_POST['name']) ? $_POST['name'] : '简历';

try{
    render($name, $mdContent);
} catch (Error $err){
    print_r($err);
}


