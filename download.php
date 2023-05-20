<?php
    // include 'details.php';
    // echo "Suprodip Sarkar";

    session_start();
    // echo $_SESSION['html'];

    require_once __DIR__ . '/vendor/autoload.php';
    $mpdf = new \Mpdf\Mpdf(['format'=>'A4-L']);
    $mpdf -> SetHTMLHeader("<h3 style='text-align:center'> Debit/Credit Report </h3>");
    // $html = "<h2>Suprodip Sarkar</h2>";
    $mpdf -> WriteHTML($_SESSION['html']);
    $mpdf -> SetHTMLFooter("<hr><p style='text-align:center'>Developed By: Suprodip Sarkar <br> page: {PAGENO}</p>");

    $file = "DebitCrebitReport.pdf";
    $mpdf -> Output($file, 'I'); // 'I' er jaygay 'D' dile direct download hobe

?>