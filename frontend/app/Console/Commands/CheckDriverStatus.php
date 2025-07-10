<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckDriverStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'drivers:check-status {--email= : Check specific driver by email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check driver verification status and diagnose access issues';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        
        if ($email) {
            $driver = User::where('email', $email)->where('role', 'driver')->first();
            if (!$driver) {
                $this->error("Driver with email '{$email}' not found!");
                return 1;
            }
            $this->displayDriverInfo($driver);
        } else {
            $drivers = User::where('role', 'driver')->get();
            $this->info("Found {$drivers->count()} drivers:");
            $this->newLine();
            
            foreach ($drivers as $driver) {
                $this->displayDriverInfo($driver);
                $this->newLine();
            }
        }
        
        return 0;
    }
    
    private function displayDriverInfo(User $driver)
    {
        $this->line("ID: {$driver->id}");
        $this->line("Name: {$driver->name}");
        $this->line("Email: {$driver->email}");
        $this->line("Role: {$driver->role}");
        $this->line("Verified: " . ($driver->is_verified ? '✅ Yes' : '❌ No'));
        $this->line("Created: {$driver->created_at}");
        $this->line("Updated: {$driver->updated_at}");
        
        // Check if driver can access ride creation
        if ($driver->isVerifiedDriver()) {
            $this->info("✅ This driver CAN access /driver/rides/create");
        } else {
            $this->error("❌ This driver CANNOT access /driver/rides/create");
            if ($driver->isUnverifiedDriver()) {
                $this->warn("   Reason: Driver is not verified");
            } elseif (!$driver->isDriver()) {
                $this->warn("   Reason: User is not a driver");
            }
        }
    }
}
