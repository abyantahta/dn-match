<?php

namespace App\Http\Controllers;

use App\Models\Interlock;
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
use Yajra\DataTables\Facades\DataTables;

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

        if (!Storage::disk('public')->exists('pdfs')) {
            Storage::disk('public')->makeDirectory('pdfs');
        }
    
        // Check and create 'qrcodes' folder if it doesn't exist
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }
        if (!Storage::disk('public')->exists('modified')) {
            Storage::disk('public')->makeDirectory('modified');
        }
    

        // Storage::disk('public/fpds')->delete();
        Storage::disk('public')->delete(Storage::disk('public')->files('pdfs'));
        Storage::disk('public')->delete(Storage::disk('public')->files('qrcodes'));
        Storage::disk('public')->delete(Storage::disk('public')->files('modified'));


        if ($request->hasFile('pdf')) {
            $file = $request->file('pdf');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('/pdfs', $filename, 'public');

            $pdf = new Fpdi();
            try{

                $pageCount = $pdf->setSourceFile(Storage::disk('public')->path('pdfs/'.$filename));
            }catch(\Exception $e){
                return back()->with('error','PDF tidak bisa diunggah karena encrypted');
            }

            $pcc_saved = 0;
            $pcc_duplicate = 0;
            $pcc_total = 0;
            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $parser = new Parser();
                $pdf_parser = $parser->parseFile($file);
                $text = $pdf_parser->getPages()[$pageNo-1]->getText();
                $arrText = preg_split("/\r\n|\r|\n/", $text);
                $collectionPCC = collect($arrText);
                $collectionPCC = $collectionPCC->forget(0)->forget(41)->values();
                // $arrText = array_shift($arrText);
                // dd($collectionPCC);
                if(!($collectionPCC[1]==="FROM:" && is_int($collectionPCC->count())/42)){
                    return back()->with('error','PDF tidak sesuai standard, silahkan upload PDF yang sesuai');
                }
                $slipBarcodes = array();
                // $i = 0;
                // dd($collectionPCC);
                
                // while($i<$collectionPCC->count()){
                    
                    $pccInOnePage = ($collectionPCC->count())/42;

                    // dd($arrText[40],$qty_packingVar,$slip_noVar,$pcc_countVar);
                    // dd($pccInOnePage);
                    // dd($collectionPCC);
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
                            $prod_seq_no = $collectionPCC[24+$pccCounter*42] == ""?null:$collectionPCC[24+$pccCounter*42];
                            $kd_lot_no = $collectionPCC[26+$pccCounter*42] ;
                            $slip_no = $slip_noVar;
                            $pcc_count = $pcc_countVar;
                            $qty_packing = $qty_packingVar;
                            $ship = $collectionPCC[28+$pccCounter*42];
                            $date = $collectionPCC[30+$pccCounter*42];
                            $time = $collectionPCC[32+$pccCounter*42];
                            $hns = $collectionPCC[34+$pccCounter*42];
                            $slip_barcode = $collectionPCC[39+$pccCounter*42];

                            $pcc_total++;
                            array_push($slipBarcodes,$slip_barcode);
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
                                $pcc_saved++;
                            }else{
                                $pcc_duplicate++;
                            }
                            // dd('halo');
                    }
                foreach ($slipBarcodes as $slip_barcode){
                    
                    $writer = new PngWriter();
                    $qrCode = new QrCode(
                        data: $slip_barcode,
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
                    $result->saveToFile(Storage::disk('public')->path('qrcodes/'.$slip_barcode.'.png'));
                    
                }
                
                // Import page
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                // Add a new page with the same size
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);

                // Use the imported page
                $pdf->useTemplate($templateId);
                $offsety = 61;
                foreach ($slipBarcodes as $slipBarcode){
                    $pdf->Image(Storage::disk('public')->path('qrcodes/'.$slipBarcode.'.png'),55,$offsety,12,0,'PNG');
                    $offsety +=70;
                }

            }

            $pdf->Output(Storage::disk('public')->path('modified/'.$filename),'F');
            return back()->with('success', 'PDF uploaded successfully! <br> Total PCC : '.$pcc_total.' PCC Tersimpan : '.$pcc_saved.' PCC Duplikat: '.$pcc_duplicate)
                ->with('filename', $filename);
        }
        return back()->with('error', 'No file was uploaded.');
    }
    public function download($filename)
    {
        $path = 'modified/' . $filename;
        // dd($path);
        if (Storage::disk('public')->exists($path)) {
            // Create new PDF instance
            $pdf = new Fpdi();
            $pdf->AddPage();
            $pdf->setSourceFile(Storage::disk('public')->path($path));
            $tplId = $pdf->importPage(1);
            $pdf->useTemplate($tplId);

            return response($pdf->Output('S'), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="modified_' . $filename . '"');
        }

        return back()->with('error', 'File not found.');
    }

    public function getPCCData(Request $request)
    {
        // dd();
        $query = Pcc::query(); // Ganti dengan model yang sesuai
        if($request->created_at){
            $query->whereDate('date',$request->created_at);
        }
        // if (!empty($request->pccStatus)) {
            if ($request->pccStatus == 'matched') {
                $query->where('isMatch', 1);
            } else if ($request->pccStatus == 'unmatched') {
                $query->where('isMatch', 0);
            }

        return DataTables::of($query)
        ->addIndexColumn()
        ->editColumn('isMatch', function ($transaction) {
            return '<span class="badge text-center ' . ($transaction->isMatch? 'bg-success' : 'bg-danger') . '">' . ($transaction->isMatch? 'Matched' : 'Unmatched') . '</span>';
        })
        ->rawColumns(['isMatch']) // Jangan lupa tambahkan 'created_at' di sini
        ->make(true);
    }
    public function index()
    {
        $dateFilter = request('date_filter', '');  // default value, for instance
        $statusFilter = request('status_filter', '');
        $interlock = Interlock::get()->first();
        $dnData = Pcc::get();
        return view('pcc.index', compact('dnData','dateFilter','statusFilter','interlock'));
    }

    
}
