<?php

namespace Azuriom\Plugin\Tailwindify\Controllers\Admin;

use Azuriom\Http\Controllers\Controller;
use Azuriom\Plugin\Tailwindify\Helpers\DateHelper;
use Azuriom\Plugin\Tailwindify\Models\TailwindifyReplacement;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show the home admin page of the plugin.
     */
    public function index()
    {
        $theme = \Azuriom\Models\Setting::where('name', 'theme')->value('value') ?? 'default';
        $outputPath = base_path("resources/themes/{$theme}/assets/css/output.css");

        try {
            $lastCompilation = File::exists($outputPath)
                ? File::lastModified($outputPath)
                : null;
        } catch (\Exception $e) {
            Log::error("Erreur de lecture du fichier output.css: " . $e->getMessage());
            $lastCompilation = null;
        }

        return view('tailwindify::admin.index', [
            'theme' => $theme,
            'lastCompilation' => DateHelper::humanReadable($lastCompilation),
            'compilationExists' => File::exists($outputPath)
        ]);
    }

    public function logs()
    {
        $logPath = storage_path('logs/tailwindify.log');
        $logs = File::exists($logPath) ? File::get($logPath) : "📜 Aucun log disponible.";

        return view('tailwindify::admin.logs', compact('logs'));
    }

    public function clearLogs()
    {
        File::put(storage_path('logs/tailwindify.log'), "[" . now() . "] 📜 Logs vidés.\n");
        Log::info("🗑️ Les logs de Tailwindify ont été vidés.");
        return redirect()->route('tailwindify.admin.logs')->with('success', 'Les logs ont été vidés.');
    }

    public function bootstrap()
    {
        $bootstrapClasses = \Azuriom\Plugin\Tailwindify\Helpers\BootstrapClassScanner::scanForBootstrapClasses();
        $replacements = TailwindifyReplacement::all();

        return view('tailwindify::admin.bootstrap', compact('replacements', 'bootstrapClasses'));
    }

    public function storeReplacement(Request $request)
    {
        $request->validate([
            'bootstrap_class' => 'required|string',
            'tailwind_class' => 'required|string',
        ]);

        TailwindifyReplacement::create([
            'bootstrap_class' => $request->input('bootstrap_class'),
            'tailwind_class' => $request->input('tailwind_class'),
            'status' => 'validated',
        ]);

        return redirect()->route('tailwindify.admin.bootstrap')->with('success', 'Classe Bootstrap remplacée par Tailwind.');
    }

    public function scanBootstrapClasses(Request $request)
    {
        $bootstrapClasses = \Azuriom\Plugin\Tailwindify\Helpers\BootstrapClassScanner::scanForBootstrapClasses();

        $count = count($bootstrapClasses);

        return redirect()->route('tailwindify.admin.bootstrap')
            ->with('success', "Scan des classes Bootstrap terminé. ({$count} classes)");
    }

    public function updateReplacement(Request $request, $id)
    {
        $replacement = TailwindifyReplacement::findOrFail($id);

        $request->validate([
            'bootstrap_class' => 'required|string',
            'tailwind_class' => 'required|string',
        ]);

        $replacement->update([
            'bootstrap_class' => $request->input('bootstrap_class'),
            'tailwind_class' => $request->input('tailwind_class'),
        ]);

        return redirect()->route('tailwindify.admin.bootstrap')->with('success', 'Remplacement modifié.');
    }

    public function deleteReplacement($id)
    {
        $replacement = TailwindifyReplacement::findOrFail($id);
        $replacement->delete();

        return redirect()->route('tailwindify.admin.bootstrap')->with('success', 'Remplacement supprimé.');
    }

    public function forceCompile()
    {
        Log::info("⚙️ Déclenchement manuel de la compilation TailwindCSS...");
        Artisan::call('tailwindify:compile');

        File::append(storage_path('logs/tailwindify.log'), "[" . now() . "] ⚙️ Compilation forcée de TailwindCSS.\n");

        return redirect()->route('tailwindify.admin.index')->with('success', 'Compilation de Tailwind forcée.');
    }
}
