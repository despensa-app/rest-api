<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     * @return void
     */
    public function boot(ResponseFactory $responseFactory)
    {
        $responseFactory->macro('notFound',
            function ($message = 'No se encontraron registros.') use ($responseFactory) {
                return $responseFactory->json([
                    'error' => [
                        'message' => $message,
                    ],
                ], Response::HTTP_NOT_FOUND);
            });

        $responseFactory->macro('badRequest', function ($message) use ($responseFactory) {
            return $responseFactory->json([
                'error' => [
                    'message' => $message,
                ],
            ], Response::HTTP_BAD_REQUEST);
        });

    }
}
