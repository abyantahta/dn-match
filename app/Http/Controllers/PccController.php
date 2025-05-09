<?php

namespace App\Http\Controllers;

use App\Models\Pcc;
use Carbon\Carbon;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Http\Request;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
// use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
// use setasign\Fpdf\Fpdf;
use Illuminate\Support\Str;

use Smalot\PdfParser\Parser;




class PccController extends Controller
{
    public function showUploadForm()
    {
        return view('pcc.upload');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);
        // dd('halo');
        $folderPath = Storage::disk('public')->path('splitted'); // Adjust path as needed
        if (File::exists($folderPath)) {
            File::delete(File::files($folderPath)); // Delete all files
        }

        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('pdfs', $filename, 'public');

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile(Storage::disk('public')->path('pdfs/'.$filename));
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $parser = new Parser();
                $pdf_parser = $parser->parseFile($file);
                $text = $pdf_parser->getPages()[$pageNo-1]->getText();
                $arrText = preg_split("/\r\n|\r|\n/", $text);
                $collectionPCC = collect($arrText);
                $collectionPCC = $collectionPCC->forget(0)->forget(41)->values();
                // $arrText = array_shift($arrText);
                $partNoValueArray = array();
                $i = 0;
                // dd($collectionPCC);

                // while($i<$collectionPCC->count()){
                    $pccInOnePage = ($collectionPCC->count())/42;

                    // dd($arrText[40],$qty_packingVar,$slip_noVar,$pcc_countVar);
                    // dd($pccInOnePage);
                    for($pccCounter = 0; $pccCounter < $pccInOnePage ; $pccCounter++){
                        // dd($pccCounter,$pccInOnePage);
                            $qty_packingVar = Str::substr($collectionPCC[39+$pccCounter*42],17,6);
                            $slip_noVar = Str::substr($collectionPCC[39+$pccCounter*42],0,12);
                            $pcc_countVar = Str::substr($collectionPCC[39+$pccCounter*42],13,4);

                            $del_from = $collectionPCC[0+$pccCounter*42];
                            $del_to = Str::substr($collectionPCC[3+$pccCounter*42],4,14);
                            $supply_address = $collectionPCC[8+$pccCounter*42];
                            $ms_id = $collectionPCC[10+$pccCounter*42];
                            $inven_category = $collectionPCC[12+$pccCounter*42];
                            $part_no = $collectionPCC[14+$pccCounter*42];
                            $part_name = $collectionPCC[15+$pccCounter*42];
                            $ps_code = $collectionPCC[18+$pccCounter*42];
                            $order_class = $collectionPCC[20+$pccCounter*42];
                            $prod_seq_no = $collectionPCC[24+$pccCounter*42];
                            $kd_lot_no = $collectionPCC[26+$pccCounter*42];
                            $slip_no = $slip_noVar;
                            $pcc_count = $pcc_countVar;
                            $qty_packing = $qty_packingVar;
                            $ship = $collectionPCC[28+$pccCounter*42];
                            $date = $collectionPCC[30+$pccCounter*42];
                            $time = $collectionPCC[32+$pccCounter*42];
                            $hns = $collectionPCC[34+$pccCounter*42];
                            $slip_barcode = $collectionPCC[39+$pccCounter*42];

                            array_push($partNoValueArray,$slip_noVar);
                            $existedPcc = Pcc::where('slip_barcode',$slip_barcode)->get()->first();
                            if(!$existedPcc){
                                Pcc::create([
                                    'del_from'=>$del_from,
                                    'del_to'=> $del_to,
                                    'supply_address'=> $supply_address,
                                    'm/s_id'=> $ms_id,
                                    'inven_category'=> $inven_category,
                                    'part_no'=> $part_no,
                                    'part_name'=> $part_name,
                                    'p/s_code'=> $ps_code,
                                    'order_class'=> $order_class,
                                    'prod_seq_no'=> $prod_seq_no,
                                    'kd_lot_no'=> $kd_lot_no,
                                    'slip_no'=> $slip_no,
                                    'pcc_count'=> $pcc_count,
                                    'qty_packing'=> $qty_packing,
                                    'ship'=> $ship,
                                    // 'slip_barcode'=> $slip_barcode,
                                    'slip_barcode'=> $slip_barcode,
                                    'date'=> Carbon::createFromFormat('d-m', $date),
                                    'time'=> Carbon::parse($time),
                                    'hns'=> $hns,
                                ]);
                            }
                            // dd('halo');
                    }
                    
                    // $i = 
                    // if($i==15){
                    //     $i++; 
                    // }
                    // $i += 42;
                // };
                foreach ($partNoValueArray as $partNo){
                    
                    $writer = new PngWriter();
                    $qrCode = new QrCode(
                        data: $partNo,
                        encoding: new Encoding('UTF-8'),
                        errorCorrectionLevel: ErrorCorrectionLevel::Low,
                        size: 300,
                        margin: 10,
                        roundBlockSizeMode: RoundBlockSizeMode::Margin,
                    );
                    $result = $writer->write($qrCode);
                    // $path = public_path('qrcode.png');
                    if(!File::exists(Storage::disk('public')->path('qrcodes'))){
                        File::makeDirectory(Storage::disk('public')->path('qrcodes'),0777,true,true);
                    }
                    $result->saveToFile(Storage::disk('public')->path('qrcodes/'.$partNo.'.png'));
                    
                }
                
                // Import page
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                // Add a new page with the same size
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // Use the imported page
                $pdf->useTemplate($templateId);
                $offsety = 61;
                foreach ($partNoValueArray as $partNo){
                    $pdf->Image(Storage::disk('public')->path('qrcodes/'.$partNo.'.png'),55,$offsety,12,0,'PNG');
                    $offsety +=70;
                }
                // $pdf->Image(Storage::disk('public')->path('qr.png'),55,131,12,0,'PNG');
                // $pdf->Image(Storage::disk('public')->path('qr.png'),55,201,12,0,'PNG');
                // $pdf->Image(Storage::disk('public')->path('qr.png'),55,271,12,0,'PNG');

            }
            // $pdf->Output('S');
            // Output the modified PDF
            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="modified_' . $filename . '"');


            return back()->with('success', 'PDF uploaded successfully!')
                ->with('filename', $filename);
        }

        // try {
        //     Excel::import(new DnADMImport, $request->file('file'));
        //     return back()->with('success', 'DNs imported successfully.');
        // } catch (\Illuminate\Database\QueryException $e) {
        //     // Tangkap kesalahan SQL dan tampilkan pesan kesalahan
        //     $errorMessage = $e->getMessage();
        //     return back()->with('error', 'SQL Error: ' . $errorMessage);
        // } catch (\Exception $e) {
        //     // Tangkap kesalahan umum lainnya
        //     $errorMessage = $e->getMessage();
        //     return back()->with('error', 'Error: ' . $errorMessage);
        // }
        return back()->with('error', 'No file was uploaded.');
    }

    // public function download($filename)
    // {
    //     $path = 'pdfs/' . $filename;

    //     if (Storage::disk('public')->exists($path)) {
    //         // Create new PDF instance
    //         $pdf = new Fpdi();
            
    //         // $text = (new Pdf(Storage::disk('public')->path($path)))
    //         // ->setPdf(Storage::disk('public')->path($path))
    //         // ->text();

    //         // dd($text);
    //         // dd(Pdf::getText(Storage::disk('public')->path($path)));
    //         // Get the number of pages in the original PDF
    //         $pageCount = $pdf->setSourceFile(Storage::disk('public')->path($path));
    //         // dd("halo");

    //         // Import each page and add drawing
    //         for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    //             // Import page
    //             $templateId = $pdf->importPage($pageNo);
    //             $size = $pdf->getTemplateSize($templateId);

    //             // Add a new page with the same size
    //             $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

    //             // Use the imported page
    //             $pdf->useTemplate($templateId);

    //             // Add drawing at top-left corner (coordinates: x=10, y=10)
    //             $pdf->SetDrawColor(255, 0, 0); // Red color
    //             $pdf->SetLineWidth(2);
    //             $pdf->SetXY(150, 30);
    //             // $pdf->Write(0, 'This is just a simple text');

    //             $pdf->SetFont('Helvetica');
    //             $pdf->SetTextColor(255, 0, 0);

    //             $pdf->SetXY(150, 100);
    //             $pdf->Write(0, 'This is just a simple text');
    //             // Draw a rectangle
    //             // $pdf->SetXY(0,100);
    //             // $pdf->Rect(10, 10, 50, 30);
    //             $pdf->Image('https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/QR_Code_Example.svg/1024px-QR_Code_Example.svg.png',60,57,12,0,'PNG');
    //             $pdf->Image('https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/QR_Code_Example.svg/1024px-QR_Code_Example.svg.png',60,123,12,0,'PNG');
    //             $pdf->Image('https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/QR_Code_Example.svg/1024px-QR_Code_Example.svg.png',60,189,12,0,'PNG');
    //             $pdf->Image('https://upload.wikimedia.org/wikipedia/commons/thumb/4/41/QR_Code_Example.svg/1024px-QR_Code_Example.svg.png',60,255,12,0,'PNG');

    //             //offetX,offsetY,ratioSize,nggaktauapa
    //             // $pdf->SetXY(30,120);
    //             // Draw a circle
    //             // $pdf->Circle(35, 25, 15);

    //             // Add text
    //             $pdf->SetXY(150, 150);
    //             $pdf->SetTextColor(20, 0, 255); // Blue color
    //             $pdf->SetFont('Arial', 'B', 12);
    //             $pdf->Write(15, 20, 'Sample');
    //         }

    //         // Output the modified PDF
    //         return response($pdf->Output('S'), 200)
    //             ->header('Content-Type', 'application/pdf')
    //             ->header('Content-Disposition', 'attachment; filename="modified_' . $filename . '"');
    //     }

    //     return back()->with('error', 'File not found.');
    // }
}
