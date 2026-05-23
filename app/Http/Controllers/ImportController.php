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
use Storage;
use Throwable;
use function parseDateMultiFormat;

class ImportController extends Controller
{
    private const int COL_DATE = 0;
    private const int COL_AMOUNT = 1;
    private const int COL_TRANSAC_TYPE = 2;
    private const int COL_BENEFICIARY = 4;
    private const int COL_BENEFICIARY_BIS = 5;
    private const string FIND_CB_BENEF = '#CB\s+([\w.*\-\s]+)\s(\d{2}/\d{2}/\d{2})#';
    private const string FIND_PRLVT_BENEF = '#PRLV\sSEPA\s([\w\-\s.]+)#';
    private const string FIND_VIRT_PERMA_BENEF = '#VIR\.PERMANENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_SEPA_BENEF = '#VIR\sSEPA\s([\w\-\s]+)#';
    private const string FIND_VIRT_SIMPLE_BENEF = '#VIREMENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_INST_BENEF = '#VIR\sINST\s([\w\-\s]+)#';
    private const string FIND_VIRT_WERO_BENEF = '#VIR\sINST\sWero\s([\w\-\s]+)#';
    private const string FIND_WITHDRAWAL = '#CB\s+RETRAIT\sDU\s+(\d{2}/\d{2}(?:/\d{2})?)#';
    private const string FIND_OTHER = '#([\w\-\s.]+)\s(\d{2}/\d{2}/\d{2})#';

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

                    $benef_result = null;
                    $benef_raw = !empty($line[self::COL_BENEFICIARY])
                        ? $line[self::COL_BENEFICIARY]
                        : $line[self::COL_BENEFICIARY_BIS];

                    $transac_results = match ($line[self::COL_TRANSAC_TYPE]) {
                        'Carte' => $this->getCardInfo($benef_raw),
                        'Virement' => $this->getTransferInfo($benef_raw),
                        'Retrait DAB' => $this->getWithdrawalInfo($benef_raw),
                        default => $this->getOtherInfo($benef_raw),
                    };

                    if (!empty($transac_results['benef'])) {
                        $benef_result = Beneficiary::query()
                            ->select(['id', 'category_id'])
                            ->where('raw_name', $transac_results['benef'])
                            ->first();

                        if (empty($benef_result)) {
                            $benef_result = Beneficiary::create(['raw_name' => $transac_results['benef']]);
                        }
                    }

                    // @TODO: Vérifier si la transaction a un recurring pattern

                    Transaction::create([
                        'amount' => str_replace(',', '.', $line[self::COL_AMOUNT]),
                        'beneficiary_id' => !empty($benef_result) ? $benef_result->id : null,
                        'occurred_at' => parseDateMultiFormat($line[self::COL_DATE])->format('Y-m-d'),
                        'type' => $transac_results['type'],
                        'category_id' => !empty($benef_result->category_id) ? $benef_result->category_id : null,
                        'line' => $i,
                        'file' => $file,
                    ]);

                    $i++;
                }

                DB::commit();
                Storage::disk('statements')->move("/{$file}", "/parsed/{$file}");
            }
        }

        return redirect(route('import_index'))->with('message', 'Import successful!');
    }

    /**
     * @param mixed $benef_raw
     * @return array
     */
    private function getCardInfo(mixed $benef_raw): array
    {
        $has_match = preg_match(self::FIND_CB_BENEF, $benef_raw, $benef_matches);

        return ['type' => TransactionType::card->name, 'benef' => $has_match ? trim($benef_matches[1]) : ''];
    }

    /**
     * @param mixed $benef_raw
     * @return array
     */
    private function getTransferInfo(mixed $benef_raw): array
    {
        $type = TransactionType::perma_transfer->name;

        if (!$has_match = preg_match(self::FIND_VIRT_PERMA_BENEF, $benef_raw, $benef_matches)) {
            $type = TransactionType::transfer->name;

            // @fixme : doit y avoir moyen de faire mieux è.è
            if (!$has_match = preg_match(self::FIND_VIRT_SIMPLE_BENEF, $benef_raw, $benef_matches)) {
                if (!$has_match = preg_match(self::FIND_VIRT_INST_BENEF, $benef_raw, $benef_matches)) {
                    if (!$has_match = preg_match(self::FIND_VIRT_SEPA_BENEF, $benef_raw, $benef_matches)) {
                        $type = TransactionType::wero->name;

                        if (!$has_match = preg_match(self::FIND_VIRT_WERO_BENEF, $benef_raw, $benef_matches)) {
                            $has_match = preg_match(self::FIND_PRLVT_BENEF, $benef_raw, $benef_matches);
                            $type = TransactionType::collection->name;
                        }
                    }
                }
            }
        }

        return [
            'type' => $type,
            'benef' => !empty($has_match) ? trim($benef_matches[1]) : trim($benef_raw),
        ];
    }

    /**
     * @param mixed $benef_raw
     * @return array
     */
    private function getWithdrawalInfo(mixed $benef_raw): array
    {
        preg_match(self::FIND_WITHDRAWAL, $benef_raw, $benef_matches);

        return [
            'type' => TransactionType::withdrawal->name,
            'benef' => '',
        ];
    }

    /**
     * @param mixed $benef_raw
     * @return array
     */
    private function getOtherInfo(mixed $benef_raw): array
    {
        $has_match = preg_match(self::FIND_OTHER, $benef_raw, $benef_matches);

        return ['type' => TransactionType::other->name, 'benef' => $has_match ? $benef_matches[1] : trim($benef_raw)];
    }
}
