<?php
//============================================================+
// File name   : example_011.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 011 for TCPDF class
//               Colored Table (very simple table)
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Colored Table
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// extend TCPF with custom functions
class MYPDF extends TCPDF
{

    // Load table data from file
    public function LoadData()
    {
        include('../database/db_yeokart.php');

        $select_query = "SELECT * FROM products ORDER BY times_sold DESC LIMIT 10";
        $result_query = mysqli_query($con, $select_query);
        return $result_query;
    }

    // Colored table
    public function ColoredTable($header, $data, $orderCount, $totalItemsSold, $totalRevenue, $totalIncome)
    {
        // Colors, line width and bold font
        $this->SetFillColor(221, 47, 110);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array(60, 50, 50, 20);
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['item_name'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['category_name'], 'LR', 0, 'C', $fill);
            $this->Cell($w[2], 6, $row['artist_name'], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], 6, number_format($row['times_sold']), 'LR', 0, 'C', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
        $this->Ln(10);
        $this->Cell(array_sum($w), 0, 'Orders Received = ' . $orderCount, 1, 1, 'R', 0);
        $this->Cell(array_sum($w), 0, 'Total Items Sold = ' . $totalItemsSold, 1, 1, 'R', 0);
        $this->Cell(array_sum($w), 0, 'Sales Revenue = PHP ' . $totalRevenue, 1, 1, 'R', 0);
        $this->Cell(array_sum($w), 0, 'Total Income = PHP ' . $totalIncome, 1, 1, 'R', 0);
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Monthly Report - Yeokart');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

$selectedMonth = DateTime::createFromFormat('m', $_POST['selected_month'])->format('F');
$selectedYear = $_POST['selected_year'];

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Monthly Report (' . $selectedMonth . ', ' . $selectedYear . ')', PDF_HEADER_STRING);
// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 12);

// add a page
$pdf->AddPage();

// column titles
$header = array('Item Name', 'Category', 'Artist', 'Sold');

// data loading
$data = $pdf->LoadData();

$orderCount = 0; // Set a default value
if (isset($_POST['order_count'])) {
    $orderCount = $_POST['order_count'];
}

$totalItemsSold = 0; // Set a default value
if (isset($_POST['item_quantity'])) {
    $totalItemsSold = $_POST['item_quantity'];
}

$totalRevenue = 0; // Set a default value
if (isset($_POST['total_revenue'])) {
    $totalRevenue = $_POST['total_revenue'];
}

$totalIncome = 0; // Set a default value
if (isset($_POST['total_income'])) {
    $totalIncome = $_POST['total_income'];
}

// print colored table
$pdf->ColoredTable($header, $data, $orderCount, $totalItemsSold, $totalRevenue, $totalIncome);
// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('' . $selectedMonth . '_' . $selectedYear . '_monthly_report.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
