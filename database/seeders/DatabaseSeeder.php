<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\DomainItem;
use App\Models\DomainsItemsOwner;
use App\Models\Notification;
use App\Models\OnboardingTask;
use Illuminate\Database\Seeder;

use App\Models\Package;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Package::truncate();

        Package::create([
            'name' => 'Free Package',
            'start_limit' => 0,
            'end_limit' => 1,
            'price' => 0.00,
        ]);
        Package::create([
            'name' => 'Package A',
            'start_limit' => 501,
            'end_limit' => 2000,
            'price' => 99,
        ]);
        Package::create([
            'name' => 'Package B',
            'start_limit' => 2001,
            'end_limit' => 4000,
            'price' => 199,
        ]);
        Notification::create([
            'user_id' => 1,
            'type' => 'pending status',
            'message'=> 'Domain homegrid.com has Pending status .<br><br>',
            'url' => '/domains/items'
        ]);
        Notification::create([
            'user_id' => 1,
            'type' => 'pending status',
            'message'=> 'Domain homegrid.com has Pending status .<br><br>',
            'url' => '/domains/items/61'
        ]);
        DomainItem::create([
            'domain_id' => '61',
            'keyword' => 'HOMEGRID',
            'registration_number' => '0000000',
            'serial_number' => '90349183',
            'status_label' => 'Live/Pending',
            'status_date' => '2022-05-31',
            'status_definition' => 'SU - REGISTRATION REVIEW COMPLETE',
            'filing_date' => '2020-11-30',
            'description' => 'Position 1 Solar batteries; Off-grid power and deep cycle battery storage systems comprised of batteries, charge controllers and inverters with integrated LED bulbs and LED light fixtures for home and commerical installations
                              <br>Position 2 HOME GRID',
        ]);
        DomainItem::create([
            'domain_id' => '61',
            'keyword' => 'HOMEGRID',
            'registration_number' => '0000000',
            'serial_number' => '90755731',
            'status_label' => 'Live/Pending',
            'status_date' => '2022-05-31',
            'status_definition' => 'SU - REGISTRATION REVIEW COMPLETE',
            'filing_date' => '2020-11-30',
            'description' => 'Position 1 The mark consists of the term HOMEGRID in stylized letters.
                              <br>Position 2 Solar batteries; Off-grid power and deep cycle battery storage systems comprised of batteries, charge controllers and inverters with integrated LED bulbs and LED light fixtures for home and commerical installations
                              <br>Position 3 HOME GRID',
        ]);

        DomainsItemsOwner::create([
            'item_id' => 1,
            'keyword' => 'HOMEGRID',
            'name' => 'Stephen Catacte',
            'address1' => 'Yellowbell Street',
            'city' => 'Davao City',
            'state' => 'Davao del Sur',
            'country'=> 'Philippines',
            'postcode' => '8000'
        ]);

        DomainsItemsOwner::create([
            'item_id' => 2,
            'keyword' => 'HOMEGRID',
            'name' => 'Stephen Catacte',
            'address1' => 'Yellowbell Street',
            'city' => 'Davao City',
            'state' => 'Davao del Sur',
            'country'=> 'Philippines',
            'postcode' => '8000'
        ]);
    
        OnboardingTask::create([
            'task' => 'Join our Telegram discussions! https://t.me/+wjCBcWeUXTdiZTE9',
            'url' => 'https://t.me/+wjCBcWeUXTdiZTE9'
        ]);

        OnboardingTask::create([
            'task' => 'Edit your notification settings',
            'url' => '/settings'
        ]);
    }
}
