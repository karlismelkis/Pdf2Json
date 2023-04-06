<?php

use classes\UploadException;
use Spatie\PdfToText\Pdf;

// Composer autoload
if ( file_exists(__DIR__ . '/vendor/autoload.php') )
{
    require_once __DIR__ . '/vendor/autoload.php';
}

if ( ($_SERVER['REQUEST_METHOD'] ?? '') == 'POST' )
{
    try
    {
        $pdf_file = $_FILES['pdf_file'] ?? null;

        if ( empty($pdf_file) )
        {
            throw new UploadException(UPLOAD_ERR_NO_FILE);
        }

        if ( $pdf_file['error'] === UPLOAD_ERR_OK )
        {
            $mime_type = mime_content_type($pdf_file['tmp_name']);

            if ( $mime_type !== 'application/pdf' )
            {
                throw new UploadException(UPLOAD_ERR_EXTENSION);
            }

            $pdf_text = (new Pdf('/opt/homebrew/bin/pdftotext'))
                ->setPdf($pdf_file['tmp_name'])
                ->setOptions(['layout'])
                ->text();

            preg_match_all('/^\s*(\d+)\s+(.+?)\s+(\w+)\s+(\d+)\s+([\d.]+)\s+([\d.]+)\s+([\d.]+)/m', $pdf_text, $matches, PREG_SET_ORDER);

            $products = [];

            foreach ( $matches as $match )
            {
                $products[] = [
                    'code'     => $match[1],
                    'name'     => $match[2],
                    'unit'     => $match[3],
                    'quantity' => $match[4],
                    'price'    => $match[5],
                    'total'    => $match[6],
                    'vat'      => $match[7]
                ];
            }

            header('Content-disposition: attachment; filename=' . pathinfo($pdf_file['name'], PATHINFO_FILENAME) . '.json');
            header('Content-type: application/json');
            echo json_encode($products, JSON_PRETTY_PRINT);
            exit;
        }
        else
        {
            throw new UploadException($pdf_file['error']);
        }
    }
    catch ( Exception $e )
    {
        $error = $e->getMessage();
    }
}

require_once __DIR__ . '/templates/index.html';
