<?php

namespace Azuriom\Plugin\Tailwindify\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class CompileTailwind extends Command
{
    protected $signature = 'tailwindify:compile';
    protected $description = 'Compile TailwindCSS pour le thème actif.';

    public function handle()
    {
        $theme = \Azuriom\Models\Setting::where('name', 'theme')->value('value') ?? 'default';
        $themePath = base_path("resources/themes/{$theme}");

        if (!File::exists($themePath)) {
            $this->log("❌ Le thème '{$theme}' n'existe pas !");
            return 1;
        }

        $this->log("📢 Démarrage de la compilation pour le thème '{$theme}'...");

        $this->prepareEnvironment($themePath);

        $inputCss = "{$themePath}/assets/css/style.css";
        $outputCss = "{$themePath}/assets/css/output.css";
        $configFile = "{$themePath}/tailwind.config.js";

        $command = ['npx', 'tailwindcss', '-i', $inputCss, '-o', $outputCss, '--config', $configFile, '--minify'];

        if (PHP_OS_FAMILY === 'Windows') {
            array_unshift($command, 'cmd', '/c');
        }

        putenv('PATH=' . getenv('PATH') . ';' . base_path('node_modules/.bin'));

        $this->log("⚙️ Exécution de la commande: " . implode(' ', $command));

        $process = new Process($command, $themePath);
        $process->setTimeout(300);
        $process->run(function ($type, $buffer) {
            $this->log($buffer);
        });

        if (!$process->isSuccessful()) {
            $this->log("❌ Échec de la compilation: " . $process->getErrorOutput());
            return 1;
        }

        $size = File::exists($outputCss) ? filesize($outputCss) : 0;
        if ($size < 1000) {
            $this->log("⚠️ Attention : Le fichier compilé semble très petit ({$size} octets). Vérifiez que TailwindCSS trouve bien des classes à compiler.");
        } else {
            $this->log("✅ Compilation réussie ! Fichier généré ({$size} octets).");
        }

        return 0;
    }

    private function prepareEnvironment(string $pluginPath): void
    {
        $pluginPath = base_path("plugins/tailwindify");

        if (!File::exists("{$pluginPath}/node_modules")) {
            $this->log("📦 Installation des dépendances npm...");
            $process = new Process(['npm', 'install'], $pluginPath);
            $process->setTimeout(300);
            $process->run();
        }
    }

    private function log(string $message): void
    {
        $timestamp = Carbon::now('Europe/Paris')->format('d/m/Y H:i:s');
        Log::info($message);
        File::append(storage_path('logs/tailwindify.log'), "[{$timestamp}] " . $message . "\n");
    }
}
