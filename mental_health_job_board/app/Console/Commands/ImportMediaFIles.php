<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Modules\Job\Events\AutomaticJobExpiration;
use Modules\Job\Models\Job;
use Modules\Media\Models\MediaFile;
use Symfony\Component\Console\Helper\ProgressBar;

class ImportMediaFIles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $builder = MediaFile::query()->orderByDesc('created_at')->get();
        $progress = $this->output->createProgressBar(count($builder));

        foreach ($builder as $item) {
            if (Storage::disk('uploads')->exists($item->file_path) || Cache::has($item->file_path)) {
                $this->info('Exists ' . $item->file_path);
                $progress->advance();
                continue;
            }
            $content = @file_get_contents('https://mentalhealthcarecareers.com/uploads/' . $item->file_path);
            if ($content) {
                Storage::disk('uploads')->put($item->file_path, $content);
                $this->info('Saved ' . $item->file_path);
                sleep(1);
                $progress->advance();
                continue;
            }
            $this->info('404 ' . $item->file_path);
            Cache::put($item->file_path, 1);
            $progress->advance();
            sleep(1);
        }

        $progress->finish();

        return Command::SUCCESS;
    }
}
