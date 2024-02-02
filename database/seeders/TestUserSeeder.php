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

class testUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'samplesample@example.com',
            'password' => Hash::make('kenta0604'),
            'name' => '田中',
            'kana_name' => 'ダダ',
            'nickname' => 'test',
            'thumbnail_image_path' => 'sample',
            'archive_image_path' => 'sample',    
            'gender' => Gender::OTHER,
            'birthday' => Carbon::parse('2000-2-2'),
            'phone_number' => '09099999992',
            'postal_code' => '0000000',
            'prefecture' => Prefecture::OSAKA,
            'address' => 'テスト',
            'block' => '1-1-1',
            'status' => UserStatus::GENERAL
        ]);
    }
}
