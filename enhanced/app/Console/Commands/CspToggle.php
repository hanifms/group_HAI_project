<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CspToggle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csp:toggle {mode? : The mode to set (dev/development or prod/production)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Toggle CSP between development and production modes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $mode = $this->argument('mode');

        if (!$mode) {
            $mode = $this->choice(
                'Which CSP mode would you like to activate?',
                ['development', 'production'],
                0
            );
        }

        $mode = strtolower($mode);

        if (in_array($mode, ['dev', 'development'])) {
            $this->setDevelopmentMode();
        } elseif (in_array($mode, ['prod', 'production'])) {
            $this->setProductionMode();
        } else {
            $this->error('Invalid mode. Use "dev", "development", "prod", or "production".');
            return 1;
        }

        return 0;
    }

    /**
     * Set CSP to development mode
     */
    private function setDevelopmentMode()
    {
        $this->updateEnvFile([
            'CSP_ENABLED' => 'true',
            'CSP_REPORT_ONLY' => 'true',
            'HSTS_ENABLED' => 'false',
        ]);

        $this->info('CSP set to development mode:');
        $this->line('- CSP enabled with report-only mode');
        $this->line('- HSTS disabled');
        $this->line('- UI scripts and styles allowed');
        $this->warn('Remember to run "npm run dev" for Vite development server');
    }

    /**
     * Set CSP to production mode
     */
    private function setProductionMode()
    {
        $this->updateEnvFile([
            'CSP_ENABLED' => 'true',
            'CSP_REPORT_ONLY' => 'false',
            'HSTS_ENABLED' => 'true',
        ]);

        $this->info('CSP set to production mode:');
        $this->line('- CSP enabled in enforcing mode');
        $this->line('- HSTS enabled (ensure HTTPS is configured)');
        $this->warn('Test thoroughly before deploying to production');
    }

    /**
     * Update .env file with new values
     */
    private function updateEnvFile(array $variables)
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $this->error('.env file not found');
            return;
        }

        $envContent = file_get_contents($envPath);

        foreach ($variables as $key => $value) {
            $pattern = "/^{$key}=.*$/m";
            $replacement = "{$key}={$value}";

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n{$replacement}";
            }
        }

        file_put_contents($envPath, $envContent);
    }
}
