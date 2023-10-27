<?php

namespace App\PDF;

use PDF; // Import the base PDF class

class CustomPDF extends PDF
{
    public function generateReport($data)
    {
        $this->setPaper('letter'); // Set the paper size (e.g., 'letter', 'A4')
        $this->setTitle('Custom Report'); // Set the PDF title
        $this->setOrientation('portrait'); // Set the orientation ('portrait' or 'landscape')

        // Other settings and configurations can be added here

        // Load a view and pass data to it
        $view = view('pdf.report', compact('data'));

        // Render the view as HTML
        $html = $view->render();

        // Load the HTML into the PDF
        $this->loadHTML($html);

        // Generate and output the PDF
        return $this->stream();
    }
}
