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
    private const string FIND_CB_BENEF = '#CB\s+([\w.\-\s]+)\s(\d{2}/\d{2}/\d{2})#';
    private const string FIND_PRLVT_BENEF = '#PRLV\sSEPA\s([\w\-\s.]+)#';
    private const string FIND_VIRT_PERMA_BENEF = '#VIR\.PERMANENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_SEPA_BENEF = '#VIR\sSEPA\s([\w\-\s]+)#';
    private const string FIND_VIRT_SIMPLE_BENEF = '#VIREMENT\s([\w\-\s]+)#';
    private const string FIND_VIRT_WERO_BENEF = '#VIR\sINST\sWero\s([\w\-\s]+)#';
    private const string FIND_WITHDRAWAL_BENEF = '#CB\s+RETRAIT\sDU\s+(\d{2}/\d{2}(/\d{2})?)#';
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

                    $benef = '';
                    $date = '';
                    $benef_raw = !empty($line[self::COL_BENEFICIARY])
                        ? $line[self::COL_BENEFICIARY]
                        : $line[self::COL_BENEFICIARY_BIS];

                    $transac_results = match ($line[self::COL_TRANSAC_TYPE]) {
                        'Carte' => $this->getCardTransacInfo($benef_raw, $line[self::COL_DATE]),
                        'Virement' => $this->getTransferTransacInfo($benef_raw, $line[self::COL_DATE]),
                        'Retrait DAB' => $this->getWithdrawalTransacInfo($benef_raw, $line[self::COL_DATE]),
                        default => $this->getOtherTransacInfo($benef_raw, $line[self::COL_DATE]),
                    };

                    $benef_result = Beneficiary::query()
                        ->select(['id', 'raw_name'])
                        ->where('raw_name', $transac_results['benef'])
                        ->first();

                    if (empty($benef_result)) {
                        $benef_result = Beneficiary::create(['raw_name' => $transac_results['benef']]);
                    }

                    Transaction::create([
                        'amount' => str_replace(',', '.', $line[self::COL_AMOUNT]),
                        'beneficiary_id' => $benef_result->id,
                        'occurred_at' => $transac_results['date'],
                        'type' => $transac_results['type'],
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
     * @param string $raw_date
     * @return array
     */
    public function getCardTransacInfo(mixed $benef_raw, string $raw_date): array
    {
        $benef = '';
        $has_match = preg_match(self::FIND_CB_BENEF, $benef_raw, $benef_matches);

        if ($has_match) {
            $benef = trim($benef_matches[1]);
            $date = Carbon::createFromFormat('d/m/y', $benef_matches[2])->format('Y-m-d');
        } else {
            $date = Carbon::createFromFormat('d/m/Y', $raw_date)->format('Y-m-d');
        }

        return ['type' => TransactionType::card->name, 'benef' => $benef, 'date' => $date];
    }

    /**
     * @param mixed $benef_raw
     * @param string $raw_date
     * @return array
     */
    public function getTransferTransacInfo(mixed $benef_raw, string $raw_date): array
    {
        $type = TransactionType::transfer->name;

        if (!$has_match = preg_match(self::FIND_VIRT_PERMA_BENEF, $benef_raw, $benef_matches)) {
            // @fixme : doit y avoir moyen de faire mieux è.è
            if (!$has_match = preg_match(self::FIND_VIRT_SIMPLE_BENEF, $benef_raw, $benef_matches)) {
                if (!$has_match = preg_match(self::FIND_VIRT_SEPA_BENEF, $benef_raw, $benef_matches)) {
                    if (!$has_match = preg_match(self::FIND_VIRT_WERO_BENEF, $benef_raw, $benef_matches)) {
                        $has_match = preg_match(self::FIND_PRLVT_BENEF, $benef_raw, $benef_matches);
                        $type = TransactionType::collection->name;
                    }
                }
            }
        }

        return [
            'type' => $type,
            'benef' => !empty($has_match) ? trim($benef_matches[1]) : trim($benef_raw),
            // @TODO : Permettre plusieurs formats de date
            'date' => Carbon::createFromFormat('d/m/Y', $raw_date)->format('Y-m-d')
        ];
    }

    /**
     * @param mixed $benef_raw
     * @param string $raw_date
     * @return array
     */
    public function getWithdrawalTransacInfo(mixed $benef_raw, string $raw_date): array
    {
        preg_match(self::FIND_WITHDRAWAL_BENEF, $benef_raw, $benef_matches);

        return [
            'type' => TransactionType::withdrawal->name,
            'benef' => trim($benef_matches[1]),
            // @TODO : Permettre plusieurs formats de date (d/m/Y et d-m-y)
            'date' => Carbon::createFromFormat('d/m/Y', $raw_date)->format('Y-m-d')
        ];
    }

    /**
     * @param mixed $benef_raw
     * @param string $raw_date
     * @return array
     */
    public function getOtherTransacInfo(mixed $benef_raw, string $raw_date): array
    {
        $has_match = preg_match(self::FIND_OTHER, $benef_raw, $benef_matches);

        if ($has_match) {
            $benef = $benef_matches[1];
            $date = Carbon::createFromFormat('d/m/y', $benef_matches[2])->format('Y-m-d');
        } else {
            $benef = trim($benef_raw);
            $date = Carbon::createFromFormat('d/m/Y', $raw_date)->format('Y-m-d');
        }

        return ['type' => TransactionType::other->name, 'benef' => $benef, 'date' => $date];
    }
}
