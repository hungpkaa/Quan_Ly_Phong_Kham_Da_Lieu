<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage:
     *  php artisan user:reset-password someone@example.com
     *  php artisan user:reset-password someone@example.com --password=NewPass123
     *
     * @var string
     */
    protected $signature = 'user:reset-password
        {email : User email address}
        {--password= : New password (if omitted, you will be prompted)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a user password in the users table (bcrypt)';

    public function handle(): int
    {
        $email = (string) $this->argument('email');

        $user = User::query()->where('email', $email)->first();
        if (!$user) {
            $this->error("User not found for email: {$email}");
            return self::FAILURE;
        }

        $password = $this->option('password');
        if (!is_string($password) || trim($password) === '') {
            $password = $this->promptForPassword();
        }

        if (trim((string) $password) === '') {
            $this->error('Password cannot be empty.');
            return self::FAILURE;
        }

        $user->password = Hash::make((string) $password);
        $user->remember_token = null;
        $user->save();

        $this->info("Password updated for {$user->email} (role: {$user->role}).");
        return self::SUCCESS;
    }

    private function promptForPassword(): string
    {
        $askSecret = method_exists($this, 'secret');

        $password = $askSecret
            ? (string) $this->secret('New password')
            : (string) $this->ask('New password');

        $confirm = $askSecret
            ? (string) $this->secret('Confirm new password')
            : (string) $this->ask('Confirm new password');

        if ($password !== $confirm) {
            $this->error('Passwords do not match.');
            return '';
        }

        return $password;
    }
}
