<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RemoveUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:remove {names*} {--force : Do not ask for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove users by name (one or more names).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $names = $this->argument('names');

        $users = User::whereIn('name', $names)->get();

        if ($users->isEmpty()) {
            $this->info('No users found for the given names.');
            return 0;
        }

        $this->info('The following users were found:');
        foreach ($users as $u) {
            $this->line(" - {$u->id} : {$u->name} ({$u->email})");
        }

        if (! $this->option('force')) {
            if (! $this->confirm('Do you really want to delete these users? This action is irreversible.')) {
                $this->info('Operation cancelled.');
                return 1;
            }
        }

        DB::transaction(function () use ($users) {
            foreach ($users as $u) {
                $u->delete();
            }
        });

        $this->info('Deleted ' . $users->count() . ' user(s).');

        return 0;
    }
}
