<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Beneficiary;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Storage;
use Throwable;
use function parseDateMultiFormat;

class ImportController extends Controller
{
    private const int COL_DATE = 0;
    private const int COL_AMOUNT = 1;
    private const int COL_BENEFICIARY = 4;
    private const int COL_BENEFICIARY_BIS = 5;
    private const string FIND_TRANSAC_TYPE = '#^(CB\s+RETRAIT\b|CB\b|PRLV\sSEPA\b|VIR\sSEPA\b|PRET\sIMMOBILIER\b|VIR\sINST\sWero\b|VIR\sINST\b|VIR\.PERMANENT\b|VIREMENT\b|COTISATION\sMENSUELLE\b|REM\sCHQ\b)#';
    private const string FIND_CB_BENEF = '#CB\s+([\w.*\-\s\']+)#';
    private const string FIND_PRLVT_BENEF = '#PRLV\sSEPA\s([\w\-\s.]+)#';
    private const string FIND_VIRT_PERMA_BENEF = '#VIR\.PERMANENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_SEPA_BENEF = '#VIR\sSEPA\s([\w\-\s]+)#';
    private const string FIND_VIRT_SIMPLE_BENEF = '#VIREMENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_INST_BENEF = '#VIR\sINST\s([\w\-\s]+)#';
    private const string FIND_VIRT_WERO_BENEF = '#VIR\sINST\sWero\s([\w\-\s]+)#';

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
        foreach ($all_files as $file) {
            if (!preg_match('/\.csv$/', $file)) {
                continue;
            }

            // @FIXME : Ajouter un try/catch
            DB::beginTransaction();

            // Récupère le fichier à importer
            if ($handle = fopen(storage_path("statements/$file"), "r")) {
                $i = 1;
                $errors = [];
                $lines = 0;

                // @TODO: Ajouter un param pour choisir le séparateur
                while ($line = fgetcsv($handle, separator: ';')) {
                    if ($i === 1 || empty($line[self::COL_BENEFICIARY]) && empty($line[self::COL_BENEFICIARY_BIS])) {
                        $i++;
                        continue;
                    }

                    $benef_raw = !empty($line[self::COL_BENEFICIARY])
                        ? $line[self::COL_BENEFICIARY]
                        : $line[self::COL_BENEFICIARY_BIS];

                    $has_match = preg_match(self::FIND_TRANSAC_TYPE, $benef_raw, $matches);

                    if ($has_match) {
                        $match_type = preg_replace('/\s+/', ' ', $matches[1]);
                        $benef_raw_name = '';
                        $type = TransactionType::other->name;

                        switch ($match_type) {
                            case 'CB':
                                $has_match_benef = preg_match(self::FIND_CB_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    // Supprime les deux derniers caractères, qui sont un bout de la date de la transac
                                    $benef_raw_name = trim(substr($matches_benef[1], 0, strlen($matches_benef[1]) - 2));
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::card->name;
                                break;

                            case 'PRLV SEPA':
                                $has_match_benef = preg_match(self::FIND_PRLVT_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::collection->name;
                                break;

                            case 'VIR SEPA':
                                $has_match_benef = preg_match(self::FIND_VIRT_SEPA_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::transfer->name;
                                break;

                            case 'VIR INST Wero':
                                $has_match_benef = preg_match(self::FIND_VIRT_WERO_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::wero->name;
                                break;

                            case 'VIR INST':
                                $has_match_benef = preg_match(self::FIND_VIRT_INST_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::transfer_instant->name;
                                break;

                            case 'VIREMENT':
                                $has_match_benef = preg_match(self::FIND_VIRT_SIMPLE_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::transfer->name;
                                break;

                            case 'VIR.PERMANENT':
                                $has_match_benef = preg_match(self::FIND_VIRT_PERMA_BENEF, $benef_raw, $matches_benef);

                                if (!empty($has_match_benef)) {
                                    $benef_raw_name = $matches_benef[1];
                                } else {
                                    $errors[] = $i;
                                }

                                $type = TransactionType::perma_transfer->name;
                                break;

                            case 'REM CHQ':
                                $type = TransactionType::check->name;
                                break;

                            case 'CB RETRAIT':
                                $type = TransactionType::withdrawal->name;
                                break;

                            case 'PRET IMMOBILIER':
                                $benef_raw_name = 'PRET IMMOBILIER ECH';
                                $type = TransactionType::mortgage->name;
                                break;

                            case 'COTISATION MENSUELLE':
                                $benef_raw_name = 'COTISATION MENSUELLE CARTE';
                                $type = TransactionType::transfer->name;
                                break;
                        }

                        if (in_array($line, $errors)) {
                            continue;
                        }

                        $lines++;
                        $benef_result = null;

                        if (!empty($benef_raw_name)) {
                            $benef_result = Beneficiary::query()
                                ->select(['id', 'category_id', 'label_id'])
                                ->where('raw_name', $benef_raw_name)
                                ->first();

                            if (empty($benef_result)) {
                                $benef_result = Beneficiary::create(['raw_name' => $benef_raw_name]);
                            }
                        }

                        $transaction = Transaction::create([
                            'amount' => str_replace(',', '.', $line[self::COL_AMOUNT]),
                            'beneficiary_id' => !empty($benef_result) ? $benef_result->id : null,
                            'occurred_at' => parseDateMultiFormat($line[self::COL_DATE])->format('Y-m-d'),
                            'type' => $type,
                            'category_id' => !empty($benef_result->category_id) ? $benef_result->category_id : null,
                            'line' => $i,
                            'file' => $file,
                        ]);

                        if (!empty($benef_result->label_id)) {
                            $transaction->labels()->attach($benef_result->label_id);
                        }

                        $similar_transacs = Transaction::getSimilar($transaction, true);

                        if ($similar_transacs->isNotEmpty() && empty($benef_result->non_recurring)) {
                            $transaction->recurring_pattern_id = $similar_transacs->first()->recurring_pattern_id;
                        }

                        $transaction->save();
                    } else {
                        $errors[] = $i;
                        continue;
                    }

                    $i++;
                }

                DB::commit();
                Storage::disk('statements')->move("/{$file}", "/parsed/{$file}");
            }
        }

        $message_bag = [];

        if (!empty($lines)) {
            $message_bag['message'] = "{$lines} lignes importées avec succès !";
        }

        if (!empty($errors)) {
            $message_bag['error'] = 'Des erreurs ont eu lieu. Lignes en erreur : <pre>' . print_r($errors, true) . '</pre>';
        }

        if (empty($message_bag)) {
            $message_bag['warning'] = 'Aucune ligne trouvée.';
        }

        return redirect(route('import_index'))->with($message_bag);
    }
}
