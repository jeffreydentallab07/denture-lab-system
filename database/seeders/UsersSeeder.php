<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Clinic;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // Create Clinic
        Clinic::create([
            'username' => 'sampleclinic',  // ADD THIS
            'clinic_name' => 'Sample Dental Clinic',
            'email' => 'clinic@test.com',
            'password' => Hash::make('password'),
            'contact_number' => '09123456789',
            'address' => '123 Main Street, City',
        ]);

        // Create Technician
        User::create([
            'name' => 'John Technician',
            'email' => 'technician@test.com',
            'password' => Hash::make('password'),
            'role' => 'technician',
            'contact_number' => '09123456780',
        ]);

        // Create Laboratory (Admin)
        User::create([
            'name' => 'Admin Laboratory',
            'email' => 'laboratory@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'contact_number' => '09123456781',
        ]);

        // Create Rider
        User::create([
            'name' => 'Mike Rider',
            'email' => 'rider@test.com',
            'password' => Hash::make('password'),
            'role' => 'rider',
            'contact_number' => '09123456782',
        ]);

        echo "✅ Users created successfully!\n";
        echo "Clinic: clinic@test.com / password\n";
        echo "Technician: technician@test.com / password\n";
        echo "Laboratory: laboratory@test.com / password\n";
        echo "Rider: rider@test.com / password\n";
    }
}
