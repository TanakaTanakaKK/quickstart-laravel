<?php

namespace Database\Seeders;

use App\Enums\{
    Gender,
    Prefecture,
    UserRole
};
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'sample@example.com',
            'password' => Hash::make('kenta0604'),
            'name' => 'テストユーザー',
            'kana_name' => 'カンリシャ',
            'nickname' => 'test',
            'thumbnail_image_path' => 'testq',
            'archive_image_path' => 'tesq',    
            'gender' => Gender::OTHER,
            'birthday' => Carbon::parse('2000-2-2'),
            'phone_number' => '08099999999',
            'postal_code' => '0000000',
            'prefecture' => Prefecture::OSAKA,
            'address' => 'テスト',
            'block' => '1-1-1',
            'role' => UserRole::GENERAL
        ]);
    }
}
