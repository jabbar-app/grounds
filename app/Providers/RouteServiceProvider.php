<?php

namespace App\Providers;

use App\Models\Quiz;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Jalur default redirect pengguna setelah login.
     */
    public const HOME = '/dashboard';

    /**
     * Boot method untuk mendefinisikan route binding dan konfigurasi lainnya.
     */
    public function boot(): void
    {
        parent::boot();

        // Custom route model binding untuk Quiz (bisa pakai slug atau ID)
        Route::bind('quiz', function ($value) {
            return Quiz::where('slug', $value)->orWhere('id', $value)->firstOrFail();
        });
    }

    /**
     * Daftarkan route file di sini.
     */
    public function map(): void
    {
        $this->mapWebRoutes();
        $this->mapApiRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }

    protected function mapApiRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));
    }
}
