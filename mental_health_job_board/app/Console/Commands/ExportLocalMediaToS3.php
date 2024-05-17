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

class ExportLocalMediaToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:export:s3 {progress?}';

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
        if ($this->argument('progress')) {
            $progress = $this->output->createProgressBar(count($builder));
        }

        foreach ($builder as $item) {
            if (!Storage::disk('uploads')->exists($item->file_path) || Cache::has($item->file_path)) {
                if ($this->argument('progress')) {
                    $this->info('Not exists ' . $item->file_path);
                    $progress->advance();
                }
                continue;
            }

            $content = Storage::disk('uploads')->get($item->file_path);

            if (!empty($content)) {
                Storage::disk('s3')->put($item->file_path, $content);
                if ($this->argument('progress')) {
                    $this->info('Uploaded ' . $item->file_path);
                    $progress->advance();
                }
                sleep(1);
                continue;
            }
            $this->info('404 ' . $item->file_path);
            Cache::put($item->file_path, 1);
            if ($this->argument('progress')) {
                $progress->advance();
            }
            sleep(1);
        }

        if ($this->argument('progress')) {
            $progress->finish();
        }

        return Command::SUCCESS;
    }
}
