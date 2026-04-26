<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verificação de endereço de e-mail')
                ->line('Clique no link abaixo para verificar o seu endereço de e-mail')
                ->action('Verifique seu e-mail', $url)
                ->line('Se você não criou uma conta, ignore este e-mail.');
        });

//        Gate::define('view', fn(Model $objeto) => condicao);
    }
}
