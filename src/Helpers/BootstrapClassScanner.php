<?php

namespace Azuriom\Plugin\Tailwindify\Helpers;

use Azuriom\Models\Setting;
use Illuminate\Support\Facades\File;
use Azuriom\Plugin\Tailwindify\Models\TailwindifyReplacement;
use Illuminate\Support\Str;

class BootstrapClassScanner
{

    protected static $directoriesToScan = [
        'resources/views',
        'resources/themes/',
        'plugins/'
    ];

    protected static $fileExtensions = ['php', 'blade.php'];

    protected static $bootstrapClassPrefix = [
        // Structure de base
        'container', 'container-fluid', 'container-sm', 'container-md', 'container-lg', 'container-xl', 'container-xxl',
        'row', 'col', 'col-', 'col-sm-', 'col-md-', 'col-lg-', 'col-xl-', 'col-xxl-', 'col-auto',
        'd-', 'display-', 'flex', 'grid', 'inline-', 'inline-flex', 'block', 'none', 'position-',
        'position-fixed', 'position-relative', 'position-absolute', 'position-sticky',

        // Composants de bouton
        'btn', 'btn-', 'btn-lg', 'btn-sm', 'btn-group', 'btn-group-', 'btn-outline-', 'btn-link', 'btn-block', 'btn-primary', 'btn-secondary', 'btn-success', 'btn-danger', 'btn-warning', 'btn-info', 'btn-light', 'btn-dark', 'btn-link',

        // Composants de carte
        'card', 'card-body', 'card-header', 'card-footer', 'card-title', 'card-text', 'card-img-top', 'card-img-bottom', 'card-group', 'card-columns',

        // Formulaires
        'form-', 'form-control', 'form-check', 'form-check-input', 'form-check-label', 'form-select', 'form-switch', 'form-range', 'form-text', 'form-label', 'form-inline', 'form-group',

        // Alerte
        'alert', 'alert-', 'alert-primary', 'alert-secondary', 'alert-success', 'alert-danger', 'alert-warning', 'alert-info', 'alert-light', 'alert-dark', 'alert-dismissible', 'alert-link',

        // Tableaux
        'table', 'table-', 'table-striped', 'table-bordered', 'table-hover', 'table-sm', 'table-responsive', 'table-dark',

        // Grille
        'row-cols-', 'row-cols-auto', 'col-md-auto', 'col-lg-auto',

        // Navbar
        'navbar', 'navbar-', 'navbar-light', 'navbar-dark', 'navbar-expand', 'navbar-expand-sm', 'navbar-expand-md', 'navbar-expand-lg', 'navbar-expand-xl', 'navbar-expand-xxl',
        'navbar-brand', 'navbar-nav', 'navbar-toggler', 'navbar-toggler-icon',

        // Modal
        'modal', 'modal-dialog', 'modal-content', 'modal-header', 'modal-body', 'modal-footer', 'modal-title', 'modal-backdrop', 'modal-sm', 'modal-lg',

        // Dropdown
        'dropdown', 'dropdown-', 'dropdown-menu', 'dropdown-toggle', 'dropdown-item', 'dropdown-header', 'dropdown-divider',

        // Pagination
        'pagination', 'pagination-', 'pagination-item', 'pagination-link', 'pagination-lg', 'pagination-sm',

        // Toast
        'toast', 'toast-header', 'toast-body', 'toast-show', 'toast-hidden',

        // Spinners
        'spinner-border', 'spinner-grow', 'spinner-border-sm', 'spinner-grow-sm',

        // Utilitaires de couleurs et typographie
        'text-', 'text-muted', 'text-primary', 'text-secondary', 'text-success', 'text-danger', 'text-warning', 'text-info', 'text-light', 'text-dark', 'text-body', 'text-white', 'text-center', 'text-left', 'text-right',
        'bg-', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-danger', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark', 'bg-body', 'bg-white',
        'border-', 'border-top', 'border-right', 'border-bottom', 'border-left', 'border-none', 'border-muted', 'border-primary', 'border-secondary', 'border-success', 'border-danger', 'border-warning', 'border-info', 'border-light', 'border-dark',
        'shadow', 'shadow-sm', 'shadow-lg', 'shadow-none',

        // Espacement et alignement
        'm-', 'mt-', 'mr-', 'mb-', 'ml-', 'mx-', 'my-', 'p-', 'pt-', 'pr-', 'pb-', 'pl-', 'px-', 'py-',
        'm-auto', 'p-auto', 'align-items-', 'justify-content-', 'text-start', 'text-end', 'align-baseline', 'align-top', 'align-middle', 'align-bottom', 'align-text-bottom',

        // Autres utilitaires
        'hidden', 'visible', 'opacity-', 'opacity-0', 'opacity-25', 'opacity-50', 'opacity-75', 'opacity-100', 'zindex-', 'stretched-link', 'container-fluid',

        // Classes pour les boutons de radio, checkboxes et autres éléments interactifs
        'custom-control', 'custom-control-input', 'custom-control-label', 'custom-radio', 'custom-checkbox',

        // Classes pour les carrousels, accordéons et autres composants dynamiques
        'carousel', 'carousel-item', 'carousel-control-prev', 'carousel-control-next', 'carousel-indicators', 'carousel-caption',
        'accordion', 'accordion-item', 'accordion-button', 'accordion-body', 'accordion-collapse', 'accordion-flush',

        // Classes de visuel
        'img-fluid', 'img-thumbnail', 'img-responsive',

        // Classes pour les icônes, surtout avec Bootstrap Icons
        'bi-', 'bi-arrow-', 'bi-check-', 'bi-x-', 'bi-star-', 'bi-heart-', 'bi-caret-', 'bi-house-', 'bi-circle-', 'bi-gear-',

        // Autres classes utilitaires
        'lead', 'small', 'sr-only', 'sr-only-focusable',
        'visible', 'invisible', 'fixed-top', 'fixed-bottom', 'sticky-top',
        'float-', 'clearfix'
    ];

    public static function scanForBootstrapClasses(): array
    {
        $tailwindClasses = self::getTailwindClassesFromCss();
        $bootstrapClasses = [];

        foreach (self::$directoriesToScan as $dir) {
            if (File::exists(base_path($dir))) {
                $files = File::allFiles(base_path($dir));

                $chunkedFiles = array_chunk($files, 100);
                foreach ($chunkedFiles as $fileChunk) {
                    foreach ($fileChunk as $file) {
                        if (in_array($file->getExtension(), self::$fileExtensions)) {
                            $content = File::get($file->getRealPath());

                            preg_match_all('/class="([^"]+)"/', $content, $matches);

                            foreach ($matches[1] as $classString) {
                                $classes = explode(' ', $classString);
                                foreach ($classes as $class) {
                                    $class = trim($class);
                                    if (empty($class)) {
                                        continue;
                                    }

                                    $isTailwind = false;
                                    foreach ($tailwindClasses as $twClass) {
                                        if (strpos($twClass, ':') !== false) {
                                            $variantParts = explode(':', $twClass, 2);
                                            if ($variantParts[1] === $class) {
                                                $isTailwind = true;
                                                break;
                                            }
                                        } elseif ($twClass === $class) {
                                            $isTailwind = true;
                                            break;
                                        }
                                    }

                                    if ($isTailwind) {
                                        continue;
                                    }

                                    if (self::isBootstrapClass($class)) {
                                        $bootstrapClasses[$class] = $class;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $bootstrapClasses;
    }



    protected static function isBootstrapClass(string $class): bool
    {
        foreach (self::$bootstrapClassPrefix as $prefix) {
            if (Str::startsWith($class, $prefix)) {
                return true;
            }
        }

        return false;
    }

    protected static function getTailwindClassesFromCss(): array
    {
        $tailwindClasses = [];

        $theme = Setting::where('name', 'theme')->value('value') ?? 'default';
        $themeCssPath = base_path("resources/themes/{$theme}/assets/css/output.css");

        if (!File::exists($themeCssPath)) {
            $themeCssPath = base_path('plugins/tailwindify/assets/css/output.css');
        }

        if (File::exists($themeCssPath)) {
            $cssContent = File::get($themeCssPath);

            preg_match_all('/\.([a-zA-Z0-9_-]+)\s*[^{]*\{/', $cssContent, $matches);

            $tailwindClasses = array_filter($matches[1], function($class) {
                if (str_starts_with($class, '-') ||
                    str_starts_with($class, '\\') ||
                    str_starts_with($class, 'active') ||
                    str_starts_with($class, 'hover') ||
                    str_starts_with($class, 'focus')) {
                    return false;
                }

                return preg_match('/^([a-z]+-)?(text|bg|p|m|w|h|max|min|flex|grid|items|justify|border|shadow|space|rounded|gap|z|opacity|font|tracking|leading|line|placeholder|transform|scale|rotate|translate|skew|stroke|fill|animate|cursor|outline|ring|transition|duration|ease|delay|peer|group|select|object|align|order|gap|clear|overflow|underline|decoration|list|divide|sr-only|not|first|last|odd|even)/', $class);
            });

            $variants = ['hover:', 'focus:', 'active:', 'group-hover:', 'focus-within:', 'focus-visible:', 'disabled:', 'visited:', 'checked:', 'first:', 'last:', 'odd:', 'even:'];
            foreach ($variants as $variant) {
                preg_match_all('/\.' . preg_quote($variant, '/') . '([a-zA-Z0-9_-]+)\s*[^{]*\{/', $cssContent, $variantMatches);
                $tailwindClasses = array_merge($tailwindClasses, array_map(function($class) use ($variant) {
                    return $variant . $class;
                }, $variantMatches[1]));
            }

            $tailwindClasses = array_unique($tailwindClasses);
        }

        return $tailwindClasses;
    }


}
