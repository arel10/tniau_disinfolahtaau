<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunAllEncryptionScripts extends Command
{
    protected $signature = 'security:encrypt-all
        {--check-only : Run only verification scripts}
        {--stop-on-error : Stop execution on first failed script}';

    protected $description = 'Run all encryption migration and verification scripts from tools/ in one command.';

    public function handle(): int
    {
        $encryptScripts = [
            'encrypt_existing_chat_messages.php',
            'encrypt_existing_kontak_messages.php',
            'encrypt_existing_user_phones.php',
            'encrypt_existing_struktur_pii.php',
            'encrypt_existing_audit_logs.php',
            'encrypt_existing_login_attempts.php',
        ];

        $checkScripts = [
            'check_chat_encryption.php',
            'check_kontak_encryption.php',
            'check_user_phone_encryption.php',
            'check_struktur_encryption.php',
            'check_auditlog_encryption.php',
            'check_login_attempts_encryption.php',
        ];

        $scripts = $this->option('check-only')
            ? $checkScripts
            : array_merge($encryptScripts, $checkScripts);

        $this->info('=== Security Encryption Runner ===');
        $this->line('Base path: ' . base_path());
        $this->newLine();

        $failed = [];

        foreach ($scripts as $script) {
            $exitCode = $this->runToolScript($script);
            if ($exitCode !== 0) {
                $failed[] = $script;
                if ($this->option('stop-on-error')) {
                    $this->error('Stopped due to --stop-on-error.');
                    return 1;
                }
            }
        }

        $this->newLine();
        if (!empty($failed)) {
            $this->error('Completed with failures: ' . implode(', ', $failed));
            return 1;
        }

        $this->info('All encryption/check scripts completed successfully.');
        return 0;
    }

    private function runToolScript(string $script): int
    {
        $path = base_path('tools/' . $script);

        if (!is_file($path)) {
            $this->warn("[SKIP] {$script} (file not found)");
            return 0;
        }

        $this->line('--------------------------------------------------');
        $this->comment("Running: {$script}");

        $process = new Process([PHP_BINARY, $path], base_path());
        $process->setTimeout(null);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (!$process->isSuccessful()) {
            $this->error("[FAILED] {$script} (exit code: {$process->getExitCode()})");
            return (int) $process->getExitCode();
        }

        $this->info("[OK] {$script}");
        return 0;
    }
}
