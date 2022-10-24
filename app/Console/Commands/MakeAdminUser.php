<?php

namespace App\Console\Commands;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Console\Command;

class MakeAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin {user?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grant admin role to a User or create one with the admin role';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! is_string($this->argument('user'))) {
            $this->error('user argument must be a string');

            return Command::FAILURE;
        }

        if (empty($this->argument('user'))) {
            $user = User::factory()
                ->create(['role' => UserRoleEnum::admin()]);

            $this->info('Created new admin user');
            $this->info("id: {$user->id} | email: {$user->email}");
            $this->warn('The password is the default one set in the UserFactory class');
        } else {
            $user = User::find($this->argument('user')) ?? User::firstWhere('email', $this->argument('user'));

            if (empty($user)) {
                $this->error("User #{$this->argument('user')} does not exist");

                return Command::FAILURE;
            }

            $user->role = UserRoleEnum::admin();
            $user->save();
            $this->info("User #{$this->argument('user')} has been granted admin privileges");
        }

        return Command::SUCCESS;
    }
}
