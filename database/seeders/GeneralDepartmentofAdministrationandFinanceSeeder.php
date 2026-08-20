<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;

class GeneralDepartmentofAdministrationandFinanceSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Organization
        |--------------------------------------------------------------------------
        */

        $organization = Organization::create([
            'org_code' => 'GDAF',
            'org_name_kh' => 'អគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
            'org_name_en' => 'General Department of Administration and Finance',
            'desc' => 'អគ្គនាយកដ្ឋានសម្រាប់សាកល្បងប្រព័ន្ធវាយតម្លៃ',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Department 1
        | នាយកដ្ឋានផែនការ ស្ថិតិ និងនីតិកម្ម
        |--------------------------------------------------------------------------
        */

        $department1 = Department::create([
            'organization_id' => $organization->organization_id,
            'department_code' => 'GDAF-D01',
            'department_name_kh' => 'នាយកដ្ឋានផែនការ ស្ថិតិ និងនីតិកម្ម',
            'department_name_en' => 'Department of Planning, Statistics and Legislation',
            'desc' => 'ទទួលបន្ទុកការងារផែនការ ស្ថិតិ និងនីតិកម្ម',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Department Admin 1
        |--------------------------------------------------------------------------
        */

        User::create([
            'organization_id' => $organization->organization_id,
            'department_id' => $department1->department_id,
            'office_id' => null,

            'id_code' => 'GDAF-D01-ADM01',
            'name_kh' => 'យឹម ច័ន្ទបូរ',
            'name_en' => 'YIM CHANBO',
            'username' => 'yimchanbo',
            'gender' => 'male',
            'phone' => '092 884 772',
            'email' => 'yim.chanbo@mlvt.gov.kh',
            'position' => 'អនុប្រធាននាយកដ្ឋាន',

            'is_leader' => true,
            'password' => 'password',
            'role' => 'department_admin',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Office 1 - ការិយាល័យផែនការ
        |--------------------------------------------------------------------------
        */

        $office1 = Office::create([
            'department_id' => $department1->department_id,
            'office_code' => 'GDAF-O01',
            'office_name_kh' => 'ការិយាល័យផែនការ',
            'office_name_en' => 'Planning Office',
            'desc' => 'ការិយាល័យផែនការ',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Office 1 Leader
        |--------------------------------------------------------------------------
        */

        $this->createOfficeLeader(
            $organization,
            $department1,
            $office1,
            'GDAF-O01-L01',
            'ឌុច វណ្ណស៊ីដូ',
            'DUCH VANSIDO',
            'male',
            '1810800379',
            'duch.vansido@mlvt.gov.kh',
            'ប្រធានការិយាល័យ'
        );


        /*
        |--------------------------------------------------------------------------
        | Office 1 Users
        |--------------------------------------------------------------------------
        */

        $this->createOfficeUsers(
            $organization,
            $department1,
            $office1,
            [
                [
                    'id_code' => '1820400143',
                    'name_kh' => 'កេត បូរិន',
                    'name_en' => 'KET BORIN',
                    'gender' => 'male',
                    'phone' => '012773998',
                ],
                [
                    'id_code' => '1830300218',
                    'name_kh' => 'វ៉ា ចាន់ដូរ៉េ',
                    'name_en' => 'VA CHANDORE',
                    'gender' => 'female',
                    'phone' => '012449993',
                ],
                [
                    'id_code' => '1812100480',
                    'name_kh' => 'នាក់ ប៊ុនថុន',
                    'name_en' => 'NEAK BUNTHON',
                    'gender' => 'male',
                    'phone' => '012459597',
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Office 2 - ការិយាល័យស្ថិតិសរុប
        |--------------------------------------------------------------------------
        */

        $office2 = Office::create([
            'department_id' => $department1->department_id,
            'office_code' => 'GDAF-O02',
            'office_name_kh' => 'ការិយាល័យស្ថិតិសរុប',
            'office_name_en' => 'General Statistics Office',
            'desc' => 'ការិយាល័យស្ថិតិសរុប',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Office 2 Leader
        |--------------------------------------------------------------------------
        */

        $this->createOfficeLeader(
            $organization,
            $department1,
            $office2,
            '1890300103',
            'ជួប វណ្ណៈ',
            'CHOUP VANNAK',
            'male',
            '012789311',
            'choup.vannak@mlvt.gov.kh',
            'ប្រធានការិយាល័យ'
        );


        /*
        |--------------------------------------------------------------------------
        | Office 2 Users
        |--------------------------------------------------------------------------
        */

        $this->createOfficeUsers(
            $organization,
            $department1,
            $office2,
            [
                [
                    'id_code' => 'GDAF-O02-U01',
                    'name_kh' => 'ហេង សុវណ្ណា',
                    'name_en' => 'Heng Sovanna',
                    'gender' => 'female',
                    'phone' => '012200002',
                ],
                [
                    'id_code' => 'GDAF-O02-U02',
                    'name_kh' => 'នី វណ្ណៈ',
                    'name_en' => 'Ny Vannak',
                    'gender' => 'male',
                    'phone' => '012200003',
                ],
                [
                    'id_code' => 'GDAF-O02-U03',
                    'name_kh' => 'ចេង មាលី',
                    'name_en' => 'Cheng Maly',
                    'gender' => 'female',
                    'phone' => '012200004',
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Office 3 - ការិយាល័យទ្រព្យសម្បត្តិរដ្ឋ
        |--------------------------------------------------------------------------
        */

        $office3 = Office::create([
            'department_id' => $department1->department_id,
            'office_code' => 'GDAF-O03',
            'office_name_kh' => 'ការិយាល័យទ្រព្យសម្បត្តិរដ្ឋ',
            'office_name_en' => 'State Property Office',
            'desc' => 'ការិយាល័យទ្រព្យសម្បត្តិរដ្ឋ',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Office 3 Leader
        |--------------------------------------------------------------------------
        */

        $this->createOfficeLeader(
            $organization,
            $department1,
            $office3,
            'GDAF-O03-L01',
            'ស៊ុន រដ្ឋា',
            'Sun Ratha',
            'male',
            '012300001',
            'gdaf_o03_leader@example.com',
            'ប្រធានការិយាល័យ'
        );


        /*
        |--------------------------------------------------------------------------
        | Office 3 Users
        |--------------------------------------------------------------------------
        */

        $this->createOfficeUsers(
            $organization,
            $department1,
            $office3,
            [
                [
                    'id_code' => 'GDAF-O03-U01',
                    'name_kh' => 'ផាន់ សុជាតិ',
                    'name_en' => 'Phan Socheata',
                    'gender' => 'female',
                    'phone' => '012300002',
                ],
                [
                    'id_code' => 'GDAF-O03-U02',
                    'name_kh' => 'ទូច វីរៈ',
                    'name_en' => 'Touch Vireak',
                    'gender' => 'male',
                    'phone' => '012300003',
                ],
                [
                    'id_code' => 'GDAF-O03-U03',
                    'name_kh' => 'ឈុន ដាលីន',
                    'name_en' => 'Chhun Dalin',
                    'gender' => 'female',
                    'phone' => '012300004',
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Office 4 - ការិយាល័យលទ្ធកម្មសាធារណៈ
        |--------------------------------------------------------------------------
        */

        $office4 = Office::create([
            'department_id' => $department1->department_id,
            'office_code' => 'GDAF-O04',
            'office_name_kh' => 'ការិយាល័យលទ្ធកម្មសាធារណៈ',
            'office_name_en' => 'Public Procurement Office',
            'desc' => 'ការិយាល័យលទ្ធកម្មសាធារណៈ',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Office 4 Leader
        |--------------------------------------------------------------------------
        */

        $this->createOfficeLeader(
            $organization,
            $department1,
            $office4,
            'GDAF-O04-L01',
            'វ៉ាន់ សុភា',
            'Vann Sophea',
            'female',
            '012400001',
            'gdaf_o04_leader@example.com',
            'ប្រធានការិយាល័យ'
        );


        /*
        |--------------------------------------------------------------------------
        | Office 4 Users
        |--------------------------------------------------------------------------
        */

        $this->createOfficeUsers(
            $organization,
            $department1,
            $office4,
            [
                [
                    'id_code' => 'GDAF-O04-U01',
                    'name_kh' => 'កុយ សំណាង',
                    'name_en' => 'Koy Samnang',
                    'gender' => 'male',
                    'phone' => '012400002',
                ],
                [
                    'id_code' => 'GDAF-O04-U02',
                    'name_kh' => 'យ៉ន ស្រីនាង',
                    'name_en' => 'Yon Sreynang',
                    'gender' => 'female',
                    'phone' => '012400003',
                ],
                [
                    'id_code' => 'GDAF-O04-U03',
                    'name_kh' => 'ឌី សុវណ្ណ',
                    'name_en' => 'Dy Sovann',
                    'gender' => 'male',
                    'phone' => '012400004',
                ],
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Department 2
        | លេខាធិការដ្ឋាននៃអគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ
        | NO OFFICE
        |--------------------------------------------------------------------------
        */

        $department2 = Department::create([
            'organization_id' => $organization->organization_id,
            'department_code' => 'GDAF-D02',
            'department_name_kh' => 'លេខាធិការដ្ឋាននៃអគ្គនាយកដ្ឋានរដ្ឋបាល និងហិរញ្ញវត្ថុ',
            'department_name_en' => 'Secretariat of General Department of Administration and Finance',
            'desc' => 'លេខាធិការដ្ឋានដែលមិនមានការិយាល័យ',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Department 2 Admin / Leader
        |--------------------------------------------------------------------------
        */

        User::create([
            'organization_id' => $organization->organization_id,
            'department_id' => $department2->department_id,
            'office_id' => null,

            'id_code' => 'GDAF-D02-ADM01',
            'name_kh' => 'យ៉ោក សុទ្ធីឧត្តម',
            'name_en' => 'YOK SOTHYOUDOM',
            'username' => 'oudom',
            'gender' => 'male',
            'phone' => '089 377 753',
            'email' => 'gdaf.d02.admin@example.com',
            'position' => 'ប្រធានលេខាធិការដ្ឋាន',

            'is_leader' => true,
            'password' => 'password',
            'role' => 'department_admin',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Department 2 Users
        | Directly under department - NO OFFICE
        |--------------------------------------------------------------------------
        */

        $this->createDepartmentUsers(
            $organization,
            $department2,
            [
                [
                    'id_code' => 'GDAF-D02-U01',
                    'name_kh' => 'រ័ត្ន សុវណ្ណ',
                    'name_en' => 'Roth Sovann',
                    'gender' => 'male',
                    'phone' => '012500002',
                ],
                [
                    'id_code' => 'GDAF-D02-U02',
                    'name_kh' => 'មុំ ស្រីពៅ',
                    'name_en' => 'Mom Sreypov',
                    'gender' => 'female',
                    'phone' => '012500003',
                ],
                [
                    'id_code' => 'GDAF-D02-U03',
                    'name_kh' => 'គង់ វិសាល',
                    'name_en' => 'Kong Visal',
                    'gender' => 'male',
                    'phone' => '012500004',
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Create Office Leader
    |--------------------------------------------------------------------------
    */

    private function createOfficeLeader(
        Organization $organization,
        Department $department,
        Office $office,
        string $idCode,
        string $nameKh,
        string $nameEn,
        string $gender,
        string $phone,
        string $email,
        string $position
    ): void {

        User::create([
            'organization_id' => $organization->organization_id,
            'department_id' => $department->department_id,
            'office_id' => $office->office_id,

            'id_code' => $idCode,
            'name_kh' => $nameKh,
            'name_en' => $nameEn,
            'username' => strtolower($idCode),
            'gender' => $gender,
            'phone' => $phone,
            'email' => $email,
            'position' => $position,

            'is_leader' => true,
            'password' => 'password',
            'role' => 'user',
            'status' => 'active',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Create Users Under Office
    |--------------------------------------------------------------------------
    */

    private function createOfficeUsers(
        Organization $organization,
        Department $department,
        Office $office,
        array $users
    ): void {

        foreach ($users as $user) {

            User::create([
                'organization_id' => $organization->organization_id,
                'department_id' => $department->department_id,
                'office_id' => $office->office_id,

                'id_code' => $user['id_code'],
                'name_kh' => $user['name_kh'],
                'name_en' => $user['name_en'],
                'username' => strtolower($user['id_code']),
                'gender' => $user['gender'],
                'phone' => $user['phone'],
                'email' => strtolower($user['id_code']) . '@example.com',
                'position' => 'មន្ត្រី',

                'is_leader' => false,
                'password' => 'password',
                'role' => 'user',
                'status' => 'active',
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Create Users Directly Under Department
    |--------------------------------------------------------------------------
    */

    private function createDepartmentUsers(
        Organization $organization,
        Department $department,
        array $users
    ): void {

        foreach ($users as $user) {

            User::create([
                'organization_id' => $organization->organization_id,
                'department_id' => $department->department_id,
                'office_id' => null,

                'id_code' => $user['id_code'],
                'name_kh' => $user['name_kh'],
                'name_en' => $user['name_en'],
                'username' => strtolower($user['id_code']),
                'gender' => $user['gender'],
                'phone' => $user['phone'],
                'email' => strtolower($user['id_code']) . '@example.com',
                'position' => 'មន្ត្រី',

                'is_leader' => false,
                'password' => 'password',
                'role' => 'user',
                'status' => 'active',
            ]);
        }
    }
}