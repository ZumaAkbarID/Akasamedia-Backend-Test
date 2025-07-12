<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'name' => 'John Doe',
                'image' => 'employees/example2.jpg',
                'phone' => '07345678978',
                'division_id' => Division::query()->inRandomOrder()->first()->id,
                'position' => 'Backend Developer',
            ],
            [
                'name' => 'Rahmat Wahyuma Akbar',
                'image' => 'employees/example1.jpg',
                'phone' => '2132423432',
                'division_id' => Division::query()->inRandomOrder()->first()->id,
                'position' => 'FrontEnd Developer',
            ]
        ];

        foreach ($employees as $employee) {
            Employee::create($employee);
        }
    }
}
