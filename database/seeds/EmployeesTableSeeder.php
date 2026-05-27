<?php

use App\Employee;
use Illuminate\Database\Seeder;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('employees')->delete();

        $employees = [
            ['Aarav Sharma', 'aarav.sharma@stafftrack.test', 'Engineering', 780000, '2021-04-12', 'active'],
            ['Diya Iyer', 'diya.iyer@stafftrack.test', 'Human Resources', 620000, '2020-08-03', 'active'],
            ['Arjun Nair', 'arjun.nair@stafftrack.test', 'Finance', 710000, '2019-11-18', 'active'],
            ['Meera Reddy', 'meera.reddy@stafftrack.test', 'Marketing', 560000, '2022-02-07', 'inactive'],
            ['Kabir Khan', 'kabir.khan@stafftrack.test', 'Operations', 690000, '2021-09-22', 'active'],
            ['Ananya Gupta', 'ananya.gupta@stafftrack.test', 'Engineering', 850000, '2018-06-15', 'active'],
            ['Rohan Menon', 'rohan.menon@stafftrack.test', 'Sales', 590000, '2023-01-10', 'active'],
            ['Ishita Bose', 'ishita.bose@stafftrack.test', 'Customer Support', 470000, '2022-10-31', 'inactive'],
            ['Vikram Singh', 'vikram.singh@stafftrack.test', 'Product', 920000, '2020-03-26', 'active'],
            ['Nisha Patel', 'nisha.patel@stafftrack.test', 'Finance', 735000, '2019-07-09', 'active'],
            ['Siddharth Rao', 'siddharth.rao@stafftrack.test', 'Engineering', 805000, '2021-12-01', 'active'],
            ['Priya Krishnan', 'priya.krishnan@stafftrack.test', 'Marketing', 610000, '2020-05-14', 'active'],
            ['Karthik Subramanian', 'karthik.subramanian@stafftrack.test', 'Operations', 660000, '2018-09-04', 'inactive'],
            ['Sneha Joshi', 'sneha.joshi@stafftrack.test', 'Human Resources', 640000, '2022-04-20', 'active'],
            ['Rahul Verma', 'rahul.verma@stafftrack.test', 'Sales', 575000, '2023-06-05', 'active'],
        ];

        foreach ($employees as $employee) {
            Employee::create([
                'name' => $employee[0],
                'email' => $employee[1],
                'department' => $employee[2],
                'salary' => $employee[3],
                'join_date' => $employee[4],
                'status' => $employee[5],
            ]);
        }
    }
}
