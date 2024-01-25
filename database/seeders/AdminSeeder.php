<?php

namespace Database\Seeders;

use App\Enums\{
    Gender,
    Prefecture,
    UserStatus
};
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'admin@example.com',
            'password' => Hash::make('testtest'),
            'name' => '管理者',
            'kana_name' => 'カンリシャ',
            'nickname' => 'test',
            'thumbnail_image_path' => 'test',
            'archive_image_path' => 'test',    
            'gender' => Gender::OTHER,
            'birthday' => Carbon::parse('2000-2-2'),
            'phone_number' => '09099999999',
            'postal_code' => '0000000',
            'prefecture' => Prefecture::OSAKA,
            'address' => 'テスト',
            'block' => '1-1-1',
            'status' => UserStatus::ADMIN
        ]);
    }
}
