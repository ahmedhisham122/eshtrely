<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmailTemplatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert a record into the email_templates table
        DB::table('email_templates')->insert([
            'id' => 8,
            'name' => 'UserRegistration',
            'subject' => 'Create An Account',
            'description' => '<p>Hi {{name}},</p><p><span style="background-color: transparent;">Congratulations !!&nbsp;</span>Your Shop account has been Created successfully</p>',
            'created_at' => Carbon::now(),
        ]);

        // You can add more seed data if needed
    }
}
