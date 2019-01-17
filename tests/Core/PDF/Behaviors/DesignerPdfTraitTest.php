<?php
namespace Tests\Rcnchris\Core\PDF\Behaviors;

use Rcnchris\Core\PDF\PdfDoc;
use Tests\Rcnchris\Core\PDF\PdfTestCase;

class DesignerPdfTraitTest extends PdfTestCase
{
    /**
     * @var DesignerPdf
     */
    protected $pdf;

    /**
     * Arbre
     * @var array
     */
    private $tree = [
        'Operating Systems' => [
            'Microsoft Windows' => [
                '3.1' => 'NotAvailable',
                'NT' => '$120.00',
                '95' => '$120.00',
                '98' => '$120.00',
                '2000' => [
                    'Home' => '$120.00',
                    'Professional' => '$320.00',
                    'Server' => '$1200.00'
                ],
                'ME' => 'NotAvailable',
                'XP' => 'NotAvailable'
            ],
            'Linux' => [
                'Red Hat',
                'Debian',
                'Mandrake'
            ],
            'FreeBSD',
            'AS400',
            'OS/2'
        ],
        'Food' => [
            'Fruits' => [
                'Apple',
                'Pear'
            ],
            'Vegetables' => [
                'Carot',
                'Salad',
                'Bean'
            ],
            'Chicken',
            'Hamburger'
        ]
    ];

    /**
     * @param string|null $className Nom de la classe du document PDF
     * @param bool|null   $withPage  N'ajoute pas de première page si false
     *
     * @return \Tests\Rcnchris\Core\PDF\Behaviors\DesignerPdf
     * @throws \Exception
     */
    public function makePdf($className = null, $withPage = true)
    {
        return parent::makePdf(DesignerPdf::class, $withPage);
    }

    public function testDrawReturnInstance()
    {
        $this->assertInstanceOf(
            PdfDoc::class,
            $this->makePdf()->draw('rect', [])
        );
    }

    public function testDrawWithWrongFormName()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->draw('fake');
    }

    public function testDrawLine()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('line', [
            'lnBefore' => 5,
            'lnAfter' => 0
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawLineWithlnAfter()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('line', [
            'lnBefore' => 5,
            'lnAfter' => 2
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawRectangle()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawRectangleWithStyleF()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => 'F'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawRectangleWithStyleFD()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('rect', [
            'x' => 20,
            'y' => 40,
            'w' => 50,
            'h' => 25,
            'r' => 6,
            'corners' => '1234',
            'style' => 'FD'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawCircle()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawCircleWithStyleF()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => 'F'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawCircleWithStyleFD()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('circle', [
            'x' => 20,
            'y' => 40,
            'r' => 12,
            'style' => 'FD'
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawEllipse()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('ellipse', [
            'x' => 20,
            'y' => 40,
            'rx' => 12,
            'ry' => 6,
            'style' => ''
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawGrid()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('grid', [
            'spacing' => 5
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawEan13()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('ean', [
            'x' => 10,
            'y' => 40,
            'barcode' => '123456789012',
            'h' => 16,
            'w' => .35
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawUpca()
    {
        $pdf = $this->makePdf();
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->draw('upca', [
            'x' => 10,
            'y' => 40,
            'barcode' => '123456789012',
            'h' => 16,
            'w' => .35
        ])->toFile($fileDest);
        $fileDest .= '.pdf';
        $this->assertTrue(file_exists($fileDest));
        $this->addUsedFile($fileDest);
        $pdf->Close();
    }

    public function testDrawEan13WithWrongCode()
    {
        $this->expectException(\Exception::class);
        $this->makePdf()->draw('ean', [
            'x' => 10,
            'y' => 40,
            'barcode' => '1234567890123',
            'h' => 16,
            'w' => .35,
        ]);
    }


    public function testDrawCode39()
    {
        $pdf = $this->makePdf();
        $this->assertInstanceOf(PdfDoc::class, $pdf->draw('code39', [
            'x' => 10,
            'y' => 40,
            'code' => 'test code 39',
            'baseline' => .5,
            'h' => 5
        ]));
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest . '.pdf');
        $pdf->Close();
    }

    public function testTree1()
    {
        $pdf = $this->makePdf();
        $pdf->SetMargins(5, 0, 5);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Arial', '', 5);
        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(20, 20, 20);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, 'My Tree Example', 0, '', 'R');
        $pdf->Ln(6);
        $pdf->SetY(25);
        $pdf->draw('tree', ['data' => $this->tree]);
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest . '.pdf');
        $pdf->Close();
    }

    public function testTree2()
    {
        $pdf = $this->makePdf();
        $pdf->SetMargins(5, 0, 5);
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->SetFont('Arial', '', 5);
        $pdf->SetFillColor(150, 150, 150);
        $pdf->SetDrawColor(20, 20, 20);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 6, 'My Tree Example', 0, '', 'R');
        $pdf->Ln(6);
        $startX = 30;
        $nodeFormat = '[Node: %k]';
        $childFormat = '[Child: %k = <%v>]';
        $w = 40;
        $h = 5;
        $border = 0;
        $fill = 0;
        $align = 'L';
        $indent = 2;
        $vspacing = 1;
        $pdf->SetY(6);
        $pdf->draw(
            'tree',
            [$this->tree, $startX, $nodeFormat, $childFormat, $w, $h, $border, $fill, $align, $indent, $vspacing]
        );
        $fileDest = $this->resultPath . '/' . __FUNCTION__;
        $pdf->toFile($fileDest);
        $this->assertTrue(file_exists($fileDest . '.pdf'));
        $this->addUsedFile($fileDest . '.pdf');
        $pdf->Close();
    }
}
