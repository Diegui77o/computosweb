<?php 
// Plantilla basica para utilizar en reportes - Define un header y footer para usuar en hojas tamaño A4
require '../public/plugins/fpdf/fpdf.php';

class PDF extends FPDF
{
	// Constructor setea la hoja
    public function __construct()
    {
        parent::__construct('P', 'mm', 'A4');
    }
    // Cabecera de página
    function Header(){
    	$this->Rect(1, 1, 208, 31);
    	$this->Rect(1, 31, 208, 1, 'DF');
    	// Logo
    	$this->Image('../public/img/logo2.png', 2, 5, 60);
    	// Arial Bold 15
    	$this->SetFont('Arial', 'B', 14);
    	// Movernos a la derecha
    	$this->Cell(30);
    	// Titulo
		//$this->Cell ( 130, 45, 'Listado de Productos', 0, 0, 'C' );
		$this->Ln ( 2 );
		$this->SetFont ( 'Arial', 'B', 10 );
		$this->Cell ( 36 );
		
		$this->SetFont ( 'Arial', '', 7 );
		$this->Line ( 1, 32, 208, 32 );

		$this->Text ( 5, 36, 'Nombre' );
		$this->Text ( 90, 36, 'Marca' );
		$this->Text ( 180, 36, 'Stock' );

		$this->Line ( 1, 38, 209, 38 );
		$this->Line ( 1, 39, 209, 39 );
		// Salto de linea
		$this->Ln ( 10 );
		$this->SetY ( 45 );
    }

	// Pie de pagina
	function Footer() {
		// Posicion: A 1,5 cm del final
		$this->SetY ( - 15 );
		// Arial italic 8
		$this->SetFont ( 'Arial', 'I', 7 );
		// Numero de la pagina
		$this->Cell ( 0, - 3, utf8_decode ( "Páginas " ) . $this->PageNo () . '/{nb}', 0, 0, 'C' );
		$fecha = date ( "Y-m-d" );
		$hora = date ( "H:i:s" );
		$this->Line ( 1, 278, 208, 278 );
		$this->Line ( 1, 284, 208, 284 );
		$fecha = date ( "d-m-Y" );

		$this->Text ( 5, 281.5, "Reporte generado: " . $fecha );
		$this->Text ( 162, 281.5, utf8_decode ( "Cómputos Web - Listado de Productos" ) );
	}
}