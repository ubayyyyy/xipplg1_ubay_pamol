<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Dompdf\Dompdf;
use Dompdf\Options;

class Dompdf_lib {

    private $dompdf; // Deklarasi properti

    public function __construct()
    {
        // Masukkan DOMPDF (autoload)
        require_once(APPPATH . 'third_party/dompdf/autoload.inc.php');
        
        // Membuat instance DOMPDF
        $this->dompdf = new Dompdf();
    }

    public function loadHtml($html)
    {
        $this->dompdf->loadHtml($html);
    }

    public function setPaper($size, $orientation)
    {
        $this->dompdf->setPaper($size, $orientation);
    }

    public function render()
    {
        $this->dompdf->render();
    }

    public function stream($file_name = "document.pdf")
    {
        $this->dompdf->stream($file_name);
    }

    public function output()
    {
        return $this->dompdf->output();
	}
}
