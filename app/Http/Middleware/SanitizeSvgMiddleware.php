<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use enshrined\svgSanitize\Sanitizer;

class SanitizeSvgMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('svg_icon')) {
            $svg = $request->input('svg_icon');

            if (!empty($svg)) {
                $sanitizer = new Sanitizer();
                $sanitizer->minify(true);

                // Add extra allowed attributes if needed
                $sanitizer->addAllowedAttributes(['width', 'height', 'viewBox', 'preserveAspectRatio']);

                // Clean the SVG
                $cleanSvg = $sanitizer->sanitize($svg);

                // Update the request with the clean SVG
                $request->merge(['svg_icon' => $cleanSvg]);
            }
        }

        return $next($request);
    }
}
