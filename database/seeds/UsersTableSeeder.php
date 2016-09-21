<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 插入批量随机用户
        $users = factory('App\Models\User', 50)->make()->toArray();
        \App\Models\User::insert($users);
        // 插入特定用户
        factory('App\Models\User')->create([
            'mobile'      => '13714909070',
            'password' => bcrypt('qq669846'),
        ]);
    }
}
