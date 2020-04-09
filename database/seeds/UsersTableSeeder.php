<?php

use Illuminate\Database\Seeder;

use App\Models\Status;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        Status::truncate();

        factory(User::class)->create([
            'name' => 'elder',
            'email' => 'elder@email.com'
            ]);
        factory(Status::class, 10)->create();
    }
}
