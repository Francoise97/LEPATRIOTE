<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class PromoteAdmin extends Command
{
    protected $signature = 'admin:promote {email : Adresse du compte à promouvoir} {--yes : Confirmer sans prompt interactif}';

    protected $description = 'Accorder le rôle administrateur à un compte existant';

    public function handle(): int
    {
        $user = User::query()->where('email', $this->argument('email'))->first();

        if (! $user) {
            $this->error('Aucun compte ne correspond à cette adresse. Créez d’abord le compte.');

            return self::FAILURE;
        }

        if ($user->isAdmin()) {
            $this->info('Ce compte est déjà administrateur.');

            return self::SUCCESS;
        }

        if (! $this->option('yes') && ! $this->confirm("Accorder les droits administrateur à {$user->email} ?")) {
            return self::SUCCESS;
        }

        $user->forceFill(['role' => 'admin'])->save();
        $this->info("Le compte {$user->email} est maintenant administrateur.");

        return self::SUCCESS;
    }
}
