<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;

class PdfService
{
    public function generatePdf($html): string
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($pdfOptions);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    public function downloadPdf($html, $filename): void
    {
        $pdf = $this->generatePdf($html);
        $response = new Response($pdf);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.pdf"');
        $response->headers->set('Content-Length', strlen($pdf));
        $response->send();
    }

}