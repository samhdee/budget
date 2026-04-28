<?php

namespace App\Console\Commands;

use App\Crawler;
use App\DB;
use Illuminate\Console\Command;
use Storage;

class ParseBankStatementsCommand extends Command
{
    protected $signature = 'statements:parse';

    protected $description = 'Parse et importe les fichiers CSV issus des relevés bancaires.';

    /**
     * @return void
     */
    public function handle(): void
    {
        // @TODO : Voir si on peut trier pour exclure les sous-dossiers et les fichiers non-html
        $all_files = Storage::disk('statements')->allFiles('/');
        dd($all_files);

        foreach ($all_files as $file) {
            if (!preg_match('/\.csv$/', $file)) {
                continue;
            }

            // @FIXME : Ajouter un try/catch
            DB::beginTransaction();

            // Récupère le fichier à importer
            $crawler = new Crawler(file_get_contents(storage_path("web-results/{$file}")));
        }
    }
}
