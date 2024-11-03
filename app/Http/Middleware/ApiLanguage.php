<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Language as ModelLanguage;

class ApiLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->language_id){
            $language = ModelLanguage::where('id', $request->language_id)->first();
            if (!$language) {
                $language = ModelLanguage::where('default_status', 1)->first();
            }
            if ($language) {

                App::setLocale($language->short_name);
            }
        }

        return $next($request);
    }
}
