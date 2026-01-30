<?php

namespace App\Exports;

use App\Models\Order;

class OrdersExport
{
    public function export($orders = null)
    {
        if ($orders === null) {
            $orders = Order::with(['user', 'items.product'])->get();
        }

        $data = [];
        $data[] = [
            'Order ID',
            'Customer Name',
            'Full Name',
            'Email',
            'Phone',
            'Products',
            'Total',
            'Status',
            'Order Date',
        ];

        foreach ($orders as $order) {
            $data[] = [
                $order->id,
                $order->user->name ?? $order->name,
                $order->name,
                $order->email,
                $order->phone,
                $order->items->pluck('product.product_title')->implode(', '),
                $order->total,
                optional($order->status)->label(),
                $order->created_at->format('Y-m-d'),
            ];
        }

        return $data;
    }

    public function generateExcelXlsx($orders = null)
    {
        $data = $this->export($orders);
        
        // Create temporary directory
        $tempDir = sys_get_temp_dir() . '/xlsx_' . uniqid();
        @mkdir($tempDir);
        @mkdir($tempDir . '/xl');
        @mkdir($tempDir . '/xl/worksheets');
        @mkdir($tempDir . '/_rels');
        @mkdir($tempDir . '/xl/_rels');
        @mkdir($tempDir . '/docProps');

        // Create workbook.xml.rels
        $workbookRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
        file_put_contents($tempDir . '/xl/_rels/workbook.xml.rels', $workbookRels);

        // Create .rels (Package Relationships)
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
</Relationships>';
        file_put_contents($tempDir . '/_rels/.rels', $rels);

        // Create styles.xml with proper header styling
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">
  <fonts count="3">
    <font><sz val="11"/><color theme="1"/><name val="Calibri"/><family val="2"/></font>
    <font><b/><sz val="12"/><color rgb="FFFFFF"/><name val="Calibri"/><family val="2"/></font>
    <font><sz val="11"/><color theme="1"/><name val="Calibri"/><family val="2"/></font>
  </fonts>
  <fills count="3">
    <fill><patternFill patternType="none"/></fill>
    <fill><patternFill patternType="gray125"/></fill>
    <fill><patternFill patternType="solid"><fgColor rgb="366092"/></patternFill></fill>
  </fills>
  <borders count="2">
    <border><left/><right/><top/><bottom/></border>
    <border><left style="thin"><color rgb="000000"/></left><right style="thin"><color rgb="000000"/></right><top style="thin"><color rgb="000000"/></top><bottom style="thin"><color rgb="000000"/></bottom></border>
  </borders>
  <cellStyleXfs count="1">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>
  </cellStyleXfs>
  <cellXfs count="3">
    <xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>
    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" xfId="0" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"/>
    <xf numFmtId="0" fontId="2" fillId="0" borderId="1" xfId="0" applyBorder="1"/>
  </cellXfs>
</styleSheet>';
        file_put_contents($tempDir . '/xl/styles.xml', $styles);

        // Create sheet1.xml with proper column widths and cell styling
        $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheetPr filterOn="false"/>
  <dimension ref="A1:I' . count($data) . '"/>
  <sheetViews>
    <sheetView tabSelected="1" workbookViewId="0" topLeftCell="A1"/>
  </sheetViews>
  <sheetFormatPr defaultRowHeight="20" x14ac:dyDescent="0.25" xmlns:x14ac="http://schemas.microsoft.com/office/spreadsheetml/2009/9/ac"/>
  <cols>
    <col min="1" max="1" width="12" bestFit="1"/>
    <col min="2" max="2" width="18" bestFit="1"/>
    <col min="3" max="3" width="18" bestFit="1"/>
    <col min="4" max="4" width="25" bestFit="1"/>
    <col min="5" max="5" width="15" bestFit="1"/>
    <col min="6" max="6" width="30" bestFit="1"/>
    <col min="7" max="7" width="12" bestFit="1"/>
    <col min="8" max="8" width="15" bestFit="1"/>
    <col min="9" max="9" width="15" bestFit="1"/>
  </cols>
  <sheetData>';

        $rowNum = 1;
        foreach ($data as $rowIndex => $row) {
            $sheetXml .= '<row r="' . $rowNum . '" ht="25" customHeight="1">';
            $colNum = 0;
            foreach ($row as $cell) {
                $colLetter = $this->numToCol($colNum);
                $cellRef = $colLetter . $rowNum;
                $cellValue = htmlspecialchars((string)$cell, ENT_XML1, 'UTF-8');
                
                if ($rowIndex == 0) {
                    // Header row with bold, white text, blue background
                    $sheetXml .= '<c r="' . $cellRef . '" s="1" t="str"><v>' . $cellValue . '</v></c>';
                } else {
                    // Data rows with borders
                    $sheetXml .= '<c r="' . $cellRef . '" s="2" t="str"><v>' . $cellValue . '</v></c>';
                }
                $colNum++;
            }
            $sheetXml .= '</row>';
            $rowNum++;
        }

        $sheetXml .= '</sheetData>
  <pageMargins left="0.7" top="0.75" right="0.7" bottom="0.75" header="0.3" footer="0.3"/>
  <pageSetup paperSize="1" orientation="landscape"/>
  <freezePane pane="topRow" activePane="bottomRight" state="frozen" sqref="A2:I' . count($data) . '"/>
</worksheet>';
        file_put_contents($tempDir . '/xl/worksheets/sheet1.xml', $sheetXml);

        // Create workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Orders" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>';
        file_put_contents($tempDir . '/xl/workbook.xml', $workbook);

        // Create core.xml
        $core = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/officeDocument/2006/metadata/core-properties">
  <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">Bathroom eCommerce</dc:creator>
  <cp:lastModifiedBy>Bathroom eCommerce</cp:lastModifiedBy>
  <dcterms:created xmlns:dcterms="http://purl.org/dc/terms/" xsi:type="dcterms:W3CDTF" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . date('Y-m-dT H:i:sZ') . '</dcterms:created>
  <dcterms:modified xmlns:dcterms="http://purl.org/dc/terms/" xsi:type="dcterms:W3CDTF" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">' . date('Y-m-dT H:i:sZ') . '</dcterms:modified>
</cp:coreProperties>';
        file_put_contents($tempDir . '/docProps/core.xml', $core);

        // Create [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
</Types>';
        file_put_contents($tempDir . '/[Content_Types].xml', $contentTypes);

        // Create ZIP file
        $zip = new \ZipArchive();
        $zipPath = sys_get_temp_dir() . '/orders_' . uniqid() . '.xlsx';
        
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return null;
        }

        // Add all files to zip
        $this->addDirToZip($tempDir, $zip, '');
        $zip->close();

        // Clean up temp directory
        $this->removeDir($tempDir);

        return $zipPath;
    }

    private function addDirToZip($dir, &$zip, $base = '')
    {
        $handle = opendir($dir);
        while (($file = readdir($handle)) !== false) {
            if ($file === '.' || $file === '..') continue;
            $path = $dir . '/' . $file;
            if (is_dir($path)) {
                $this->addDirToZip($path, $zip, $base . $file . '/');
            } else {
                $zip->addFile($path, $base . $file);
            }
        }
        closedir($handle);
    }

    private function removeDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . '/' . $object)) {
                        $this->removeDir($dir . '/' . $object);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

    private function numToCol($num)
    {
        $num = intval($num);
        if ($num < 0) return '';
        if ($num < 26) return chr(65 + $num);
        return $this->numToCol(intval($num / 26) - 1) . chr(65 + $num % 26);
    }
}
