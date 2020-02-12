<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Http\FormRequest;

class FormServiceRequestProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
      // automatically add route params to FormRequest objects
      $this->app->resolving(FormRequest::class, function ($request, $app) {
          $request->merge($request->route()->parameters());
      });
    }
}
