<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
                                       'title' => 'Quản trị hệ thống',
                                       'desc' => 'Toàn quyền thao tác hệ thống',
                                       'created_at' => \Carbon\Carbon::now(),
                                       'updated_at' => \Carbon\Carbon::now()
                                   ]);
        DB::table('roles')->insert([
                                       'title' => 'Quản trị website',
                                       'desc' => 'Toàn quyền thao tác website',
                                       'created_at' => \Carbon\Carbon::now(),
                                       'updated_at' => \Carbon\Carbon::now()
                                   ]);
        DB::table('roles')->insert([
                                       'title' => 'Quản trị nội dung',
                                       'desc' => 'Toàn quyền thao tác nội dung website',
                                       'created_at' => \Carbon\Carbon::now(),
                                       'updated_at' => \Carbon\Carbon::now()
                                   ]);
        DB::table('roles')->insert([
                                       'title' => 'Thành viên',
                                       'desc' => 'Thành viên bình thường',
                                       'created_at' => \Carbon\Carbon::now(),
                                       'updated_at' => \Carbon\Carbon::now()
                                   ]);
    }
}
