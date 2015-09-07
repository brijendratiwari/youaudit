<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Codeigniter: mpdf libraries
 * Tuesday, July, 19 2011
 * 
 * @author bang.webdeveloper@gmail.com
 */
require_once dirname(__FILE__) . '/mpdf/mpdf.php';

class Pdf extends mPDF
{
    function __construct()
    {
        parent::__construct();
    }
}
?>