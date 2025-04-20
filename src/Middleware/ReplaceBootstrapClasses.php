<?php
namespace Azuriom\Plugin\Tailwindify\Middleware;

use Closure;
use Illuminate\Support\Facades\Request;
use Azuriom\Plugin\Tailwindify\Helpers\BootstrapClassReplacer;
use Illuminate\Http\Response;

class ReplaceBootstrapClasses
{
    public function handle($request, Closure $next)
    {
        if (!Request::is('admin*')) {
            $response = $next($request);

            if ($response instanceof Response) {
                $content = $response->getContent();

                $modifiedContent = BootstrapClassReplacer::replaceBootstrapClasses($content);

                $response->setContent($modifiedContent);
            }

            return $response;
        }

        return $next($request);
    }
}
