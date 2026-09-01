<?php

namespace Database\Seeders;
use App\Models\Organization;
use App\Models\Department;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Organization 1
        |--------------------------------------------------------------------------
        */
        $organization1 = Organization::where('org_code', '3100000010')->firstOrFail();
        $department1 = Department::where('department_code', '3101010000')->firstOrFail();
        $department2 = Department::where('department_code', '3101000100')->firstOrFail();
        $office1 = Office::where('office_code', '3101010100')->firstOrFail();
        $office2 = Office::where('office_code', '3101010200')->firstOrFail();
        /*
        |--------------------------------------------------------------------------
        | Organization 1 Admin
        |--------------------------------------------------------------------------
        */
        $org1users = [
            [
                'id_code' => '1820100032',
                'name_kh' => 'សេង សុសាន',
                'name_en' => 'SENG SOSAN',
                'username' => 'seng.sosan',
                'gender' => 'male',
                'phone' => '012628666',
                'email' => 'seng.sosan@mlvt.gov.kh',
                'position' => 'អគ្គនាយក',
                'is_leader' => true,
                'role' => 'organization_admin',
            ],
            [
                'id_code' => '1921400087',
                'name_kh' => 'យ៉ន សារ៉េត',
                'name_en' => 'YAN SARETH',
                'username' => 'yan.sareth',
                'gender' => 'male',
                'phone' => '069611779',
                'email' => 'yan.sareth@mlvt.gov.kh',
                'position' => 'អគ្គនាយករង',
                'is_leader' => true,
                'role' => 'user',
            ],
            [
                'id_code' => '1971200077',
                'name_kh' => 'ពៅ បញ្ញារិទ្ធ',
                'name_en' => 'PAUV PANHARITH',
                'username' => 'pauv.panharith',
                'gender' => 'male',
                'phone' => '085666329',
                'email' => 'pauv.panharith@mlvt.gov.kh',
                'position' => 'អគ្គនាយករង',
                'is_leader' => true,
                'role' => 'user',
            ],
        ];
        foreach ($org1users as $org1user) {

            User::create([
                'organization_id' => $organization1->organization_id,
                'department_id' => null,
                'office_id' => null,
                'id_code' => $org1user['id_code'],
                'name_kh' => $org1user['name_kh'],
                'name_en' => $org1user['name_en'],
                'username' => $org1user['username'],
                'gender' => $org1user['gender'],
                'phone' => $org1user['phone'],
                'email' => $org1user['email'],
                'position' => $org1user['position'],
                'is_leader' => $org1user['is_leader'],
                'role' => $org1user['role'],
                'password' => 'password',
                'status' => 'active',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Department 1 Admin
        |--------------------------------------------------------------------------
        */

        $department2users = [
            [
                'id_code' => '1971200066',
                'name_kh' => 'យ៉ោក សុទ្ធីឧត្តម',
                'name_en' => 'YOK SOTHYOUDOM',
                'username' => 'sothy.oudom',
                'gender' => 'male',
                'phone' => '012100001',
                'email' => 'sothy.oudom@gmail.com',
                'position' => 'ប្រធានលេខាធិការដ្ឋាន',
                'is_leader' => true,
                'role' => 'department_admin',
            ],
            [
                'id_code' => '2890800141',
                'name_kh' => 'សុទ្ធ រតនា',
                'name_en' => 'SOTH RATANA',
                'username' => 'soth.rathana',
                'gender' => 'male',
                'phone' => '012936723',
                'email' => 'soth.rathana@gmail.com',
                'position' => 'អនុប្រធានលេខាធិការដ្ឋាន',
                'is_leader' => true,
                'role' => 'user',
            ],
            [
                'id_code' => '1931200133',
                'name_kh' => 'ថុង ម៉េងដាវិត',
                'name_en' => 'THONG MENGDAVID',
                'username' => 'thong.mengdavid',
                'gender' => 'male',
                'phone' => '0123456789',
                'email' => 'thong.mengdavid@gmail.com',
                'position' => 'អនុប្រធានលេខាធិការដ្ឋាន',
                'is_leader' => true,
                'role' => 'user',
            ],
            [
                'id_code' => '2931300091',
                'name_kh' => 'ដួង ចាន់ឌី',
                'name_en' => 'DOUNG CHANDY',
                'username' => 'doung.channdy',
                'gender' => 'female',
                'phone' => '01020846755',
                'email' => 'doung.channdy@gmail.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
                'role' => 'user',
            ],
            [
                'id_code' => '1932100423',
                'name_kh' => 'យី ពុទ្ធឌុន',
                'name_en' => 'YI PUTHDUN',
                'username' => 'yi.puthdun',
                'gender' => 'male',
                'phone' => '0888991444',
                'email' => 'yi.puthdun@gmail.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
                'role' => 'user',
            ],
        ];
        foreach ($department2users as $department2user) {

            User::create([
                'organization_id' => $organization1->organization_id,
                'department_id' => $department2->department_id,
                'office_id' => null,
                'id_code' => $department2user['id_code'],
                'name_kh' => $department2user['name_kh'],
                'name_en' => $department2user['name_en'],
                'username' => $department2user['username'],
                'gender' => $department2user['gender'],
                'phone' => $department2user['phone'],
                'email' => $department2user['email'],
                'position' => $department2user['position'],
                'is_leader' => $department2user['is_leader'],
                'password' => 'password',
                'role' => $department2user['role'],
                'status' => 'active',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Department 2 Admin
        |--------------------------------------------------------------------------
        */

        User::create([
            'organization_id' => $organization1->organization_id,
            'department_id' => $department1->department_id,
            'office_id' => null,
            'id_code' => '1810600169',
            'name_kh' => 'គង់ ធារ័ត្ន',
            'name_en' => 'KONG THEAROTH',
            'username' => 'kong.thearoth',
            'gender' => 'male',
            'phone' => '012300118',
            'email' => 'kong.thearoth@mlvt.gov.kh',
            'position' => 'ប្រធាននាយកដ្ឋាន',
            'is_leader' => true,
            'password' => 'password',
            'role' => 'department_admin',
            'status' => 'active',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Office 1 Users
        |--------------------------------------------------------------------------
        */

        $office1Users = [
            [
                'id_code' => '1711200214',
                'name_kh' => 'សាលី មុនី',
                'name_en' => 'SALY MUNY',
                'username' => 'saly.muny',
                'gender' => 'male',
                'phone' => '077852567',
                'email' => 'saly.muny@mlvt.gov.kh',
                'position' => 'អនុប្រធាននាយកដ្ឋាន',
                'is_leader' => true,
            ],

            [
                'id_code' => '1831400207',
                'name_kh' => 'សុខ សុផាណារិទ្ធ',
                'name_en' => 'SOK SOPHANARITH',
                'username' => 'sok.sophannarith',
                'gender' => 'male',
                'phone' => '092703040',
                'email' => 'sok.sophannarith@mlvt.gov.kh',
                'position' => 'ប្រធានការិយាល័យ',
                'is_leader' => true,
            ],

            [
                'id_code' => '1800700278',
                'name_kh' => 'មួង រ៉ាន់',
                'name_en' => 'MOUNG RANN',
                'username' => 'moung.rann',
                'gender' => 'male',
                'phone' => '012000003',
                'email' => 'moung.rann@email.com',
                'position' => 'អនុប្រធានការិយាល័យ',
                'is_leader' => true,
            ],
            [
                'id_code' => '2851200214',
                'name_kh' => 'ជា សុរី',
                'name_en' => 'CHEA SORY',
                'username' => 'chea.sory',
                'gender' => 'female',
                'phone' => '012702857',
                'email' => 'chea.sory@email.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
            ],
            [
                'id_code' => '1940600121',
                'name_kh' => 'ពិន សុភ័ក្ត្រ',
                'name_en' => 'PIN SOPHEAK',
                'username' => 'pin.sopheak',
                'gender' => 'male',
                'phone' => '092225182',
                'email' => 'pin.sopheak@email.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
            ],
            [
                'id_code' => '2940600180',
                'name_kh' => 'កែន សុគន្ធា',
                'name_en' => 'KEN SOKUNTHEA',
                'username' => 'ken.sokunthea',
                'gender' => 'female',
                'phone' => '010753378',
                'email' => 'ken.sokunthea@email.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
            ],

        ];

        foreach ($office1Users as $user) {

            User::create([
                'organization_id' => $organization1->organization_id,
                'department_id' => $department1->department_id,
                'office_id' => $office1->office_id,
                'id_code' => $user['id_code'],
                'name_kh' => $user['name_kh'],
                'name_en' => $user['name_en'],
                'username' => $user['username'],
                'gender' => $user['gender'],
                'phone' => $user['phone'],
                'email' => $user['email'],
                'position' => $user['position'],
                'is_leader' => $user['is_leader'],
                'password' => 'password',
                'role' => 'user',
                'status' => 'active',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Organization 1 - Office 2 Users
        |--------------------------------------------------------------------------
        */

        $office2Users = [
            [
                'id_code' => '1810800349',
                'name_kh' => 'គួយ គឿន',
                'name_en' => 'KOUY KEOUN',
                'username' => 'kouy.koeun',
                'gender' => 'male',
                'phone' => '077518511',
                'email' => 'kouy.koeun@mlvt.gov.kh',
                'position' => 'ប្រធានការិយាល័យ',
                'is_leader' => true,
            ],

            [
                'id_code' => '1802000173',
                'name_kh' => 'ព្រំ ស៊ន',
                'name_en' => 'PROM SORN',
                'username' => 'prom_sorn',
                'gender' => 'male',
                'phone' => '017555847',
                'email' => 'prom_sorn@mlvt.gov.kh',
                'position' => 'អនុប្រធានការិយាល័យ',
                'is_leader' => true,
            ],

            [
                'id_code' => '2910700265',
                'name_kh' => 'ល្វី យ៉ាណា',
                'name_en' => 'LVY YANA',
                'username' => 'lvy.yana',
                'gender' => 'female',
                'phone' => '081805036',
                'email' => 'lvy.yana@gmail.com',
                'position' => 'អនុប្រធានការិយាល័យ',
                'is_leader' => true,
            ],
            [
                'id_code' => '1881400287',
                'name_kh' => 'មាស ឆូតបូរិទ្ធ',
                'name_en' => 'MEAS CHHOTBORITH',
                'username' => 'mean.chhotborith',
                'gender' => 'male',
                'phone' => '092198827',
                'email' => 'mean.chhotborith@email.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
            ],
            [
                'id_code' => '2951400175',
                'name_kh' => 'ណុប លីហ្សា',
                'name_en' => 'NOP LYZA',
                'username' => 'nop.lyza',
                'gender' => 'female',
                'phone' => '086958884',
                'email' => 'nop.lyza@email.com',
                'position' => 'មន្ត្រី',
                'is_leader' => false,
            ],

        ];

        foreach ($office2Users as $user) {

            User::create([
                'organization_id' => $organization1->organization_id,
                'department_id' => $department1->department_id,
                'office_id' => $office2->office_id,
                'id_code' => $user['id_code'],
                'name_kh' => $user['name_kh'],
                'name_en' => $user['name_en'],
                'username' => $user['username'],
                'gender' => $user['gender'],
                'phone' => $user['phone'],
                'email' => $user['email'],
                'position' => $user['position'],
                'is_leader' => $user['is_leader'],
                'password' => 'password',
                'role' => 'user',
                'status' => 'active',
            ]);
        }

    }


}