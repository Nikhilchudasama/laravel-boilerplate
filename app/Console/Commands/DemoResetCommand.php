<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

#[Signature('app:demo-reset')]
#[Description('Resets the database and clears all media files for the demo environment.')]
class DemoResetCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->warn('Starting Demo Reset...');

        // 1. Reset Database
        $this->info('Resetting database...');
        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);
        $this->info('✅ Database reset and seeded.');

        // 2. Clear Media
        $this->info('Clearing media files...');
        $this->clearMedia();
        $this->info('✅ Media files cleared.');

        // 3. Clear Caches
        $this->info('Clearing caches...');
        $this->call('optimize:clear');
        $this->info('✅ Caches cleared.');

        $this->warn('Demo Reset Complete!');
    }

    /**
     * Clear all media files from the public storage.
     */
    protected function clearMedia(): void
    {
        // 1. Clear Public Storage (Standard Spatie Path)
        $disk = config('media-library.disk_name', 'public');
        $prefix = config('media-library.prefix', '');
        
        if ($disk === 'public') {
            $path = storage_path('app/public/' . $prefix);
            if (File::exists($path)) {
                $directories = File::directories($path);
                foreach ($directories as $directory) {
                    File::deleteDirectory($directory);
                }
                
                $files = File::files($path);
                foreach ($files as $file) {
                    if ($file->getFilename() !== '.gitignore') {
                        File::delete($file->getPathname());
                    }
                }
            }
        }

        // 2. Clear Temporary Directory
        $tempPath = config('media-library.temporary_directory_path') ?? storage_path('media-library/temp');
        if (File::exists($tempPath)) {
            File::cleanDirectory($tempPath);
        }
    }
}
