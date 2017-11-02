<?php
// Plantilla de reporte general que define un encabezado y un pie de página
require '../public/plugins/fpdf/fpdf.php';
class PDF extends FPDF
{
    // Constructor setea la hoja
    public function __construct()
    {
        // Modificar el tamaño de la hoja acá
        parent::__construct('P', 'mm', 'A4');
    }
    // Cabecera
    public function Header()
    {
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
        $this->Ln(2);
        $this->SetFont('Arial', 'B', 10);
        $this->Cell(36);

        $this->SetFont('Arial', '', 7);
        $this->Line(1, 32, 208, 32);

        $this->Text(5, 36, utf8_decode('Nº Pedido'));
        $this->Text(50, 36, 'Producto');
        $this->Text(130, 36, 'Cantidad');
        //$this->Text ( 110, 36, utf8_decode ('Descripción') );

        $this->Line(1, 38, 209, 38);
        $this->Line(1, 39, 209, 39);
        // Salto de linea
        $this->Ln(10);
        $this->SetY(45);
    }
    // Pie de pagina
    public function Footer()
    {
        // Posicion: A 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 7);
        // Numero de la página
        $this->Cell(0, -3, utf8_decode("Páginas ") . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $fecha = date("Y-m-d");
        $hora  = date("H:i:s");
        $this->Line(1, 278, 208, 278);
        $this->Line(1, 284, 208, 284);
        $fecha = date("d-m-Y");
        // $hora=date("H:i:s");
        $this->Text(5, 281.5, "Reporte generado: " . $fecha);
        $this->Text(187, 281.5, utf8_decode("Cómputos Web"));
        // $this->Text(30,270.5,$hora);
    }
}
