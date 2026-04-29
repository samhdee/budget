<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Beneficiary;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Storage;
use Symfony\Component\DomCrawler\Crawler;
use Throwable;

class ImportController extends Controller
{
    private const int COL_DATE = 0;
    private const int COL_AMOUNT = 1;
    private const int COL_TRANSAC_TYPE = 2;
    private const int COL_BENEFICIARY = 4;
    private const int COL_BENEFICIARY_BIS = 5;
    private const string FIND_CB_BENEF = '#CB\s+([\w.\s]+)\s(\d{2}/\d{2}/\d{2})#';
    private const string FIND_PRLVT_BENEF = '#PRLVT\s+SEPA\s(\w\s)+#';
    private const string FIND_VIRT_BENEF = '#VIR\.PERMANENT\s([\w\s]+)#';

    /**
     * @return View
     */
    public function index()
    {
        return view('import.index');
    }

    /**
     * @throws Throwable
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            // @TODO: ajouter le mime
            'files' => 'required|max:2048',
        ]);

        $request_file = $request->file('files');
        $path = Storage::disk('statements')->putFileAs('/', $request_file, $request_file->getClientOriginalName());

        $all_files = Storage::disk('statements')->files('/');

        // @TODO: Collecter les benefs créés pour permettre de les éditer ensuite
        $created_benefs = false;

        foreach ($all_files as $file) {
            if (!preg_match('/\.csv$/', $file)) {
                continue;
            }

            // @FIXME : Ajouter un try/catch
            DB::beginTransaction();

            // Récupère le fichier à importer
            if ($handle = fopen(storage_path("statements/$file"), "r")) {
                $i = 0;

                // @TODO: Ajouter un param pour choisir le séparateur
                while ($line = fgetcsv($handle, separator: ';')) {
                    if ($i === 0 || empty($line[self::COL_BENEFICIARY]) && empty($line[self::COL_BENEFICIARY_BIS])) {
                        $i++;
                        continue;
                    }

                    $benef = '';
                    $date = '';
                    $benef_value = !empty($line[self::COL_BENEFICIARY])
                        ? $line[self::COL_BENEFICIARY]
                        : $line[self::COL_BENEFICIARY_BIS];

                    switch ($line[self::COL_TRANSAC_TYPE]) {
                        case 'Carte':
                            $type = TransactionType::card->value;
                            $has_match = preg_match(self::FIND_CB_BENEF, $benef_value, $benef_matches);

                            if ($has_match) {
                                $benef = trim($benef_matches[1]);
                                $date = Carbon::createFromFormat('d/m/y', $benef_matches[2])->format('Y-m-d');
                            } else {
                                $date = Carbon::createFromFormat('d/m/Y', $line[self::COL_DATE])->format('Y-m-d');
                            }

                            break;

                        case 'Virement':
                            $type = TransactionType::transfer->value;

                            if (!$has_match = preg_match(self::FIND_VIRT_BENEF, $benef_value, $benef_matches)) {
                                $has_match = preg_match(self::FIND_PRLVT_BENEF, $benef_value, $benef_matches);
                                $type = TransactionType::collection->value;
                            }

                            if ($has_match) {
                                $benef = trim($benef_matches[1]);
                            }

                            // @TODO : Permettre plusieurs formats de date
                            $date = Carbon::createFromFormat('d/m/Y', $line[self::COL_DATE])->format('Y-m-d');
                            break;

                        case 'Retrait DAB':
                        default:
                            $type = TransactionType::withdrawal->value;
                            $benef = trim($benef_value);
                            // @TODO : Permettre plusieurs formats de date
                            $date = Carbon::createFromFormat('d/m/Y', $line[self::COL_DATE])->format('Y-m-d');
                    }

                    $benef_result = Beneficiary::query()
                        ->select('id')
                        ->where('raw_name', $benef)
                        ->first();

                    if (empty($benef_result)) {
                        $benef_result = Beneficiary::create(['raw_name' => $benef]);
                    }

                    Transaction::create([
                        'amount' => str_replace(',', '.', $line[self::COL_AMOUNT]),
                        'beneficiary_id' => $benef_result->id,
                        'occurred_at' => $date,
                        'type' => $type,
                    ]);

                    $i++;
                }

                //@TODO: Ajouter des logs d'import
                DB::commit();
                Storage::disk('statements')->move("/{$file}", "/parsed/{$file}");
            }
        }

        return redirect(route('import_index'))->with('message', 'Import successful!');
    }
}
