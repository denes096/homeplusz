<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateUsers extends Command
{
    protected $signature = 'migrate:users';

    protected $description = 'Migrálja a régi usereket';

    public function handle()
    {
        $this->info('Felhasználók migráció indítása...');

        $oldUsers = DB::connection('old')->table('users')->get();

        $this->info('Talált felhasználók száma: '.$oldUsers->count());
        $skipped = 0;

        foreach ($oldUsers as $row) {

            if ($row->password == '') {
                continue;
            }
            // Convert UNIX timestamps to Carbon or NULL
            $registered = $row->regtime ? Carbon::createFromTimestamp($row->regtime)->format('Y-m-d H:i:s') : null;
            $lastLogin = $row->lastlogin ? Carbon::createFromTimestamp($row->lastlogin)->format('Y-m-d H:i:s') : null;
            $prevLogin = $row->prelastlogin ? Carbon::createFromTimestamp($row->prelastlogin)->format('Y-m-d H:i:s') : null;

            $user = new User;
            $user->id = $row->Id;
            $user->name = $row->name;
            $user->email = $row->email;
            $user->password = $row->password; // ⚠️ ha hashelt, jó, ha nem, akkor külön lépés
            $user->phone = $row->phone;
            $user->phone2 = $row->phone2;

            $user->position = $row->status;
            $user->profile_picture = $row->picture ?? '../images/defaultUser.png';

            $user->registered_at = $registered;
            $user->last_login_at = $lastLogin;
            $user->previous_login_at = $prevLogin;

            $user->referer_id = $row->referens;

            $user->created_at = now();
            $user->updated_at = now();

            $user->save();
        }

        $this->info("Migráció kész! Kihagyott rekordok: {$skipped}");
    }
}
