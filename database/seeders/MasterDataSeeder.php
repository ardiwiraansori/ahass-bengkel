<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Mechanic;
use App\Models\Part;
use App\Models\ServiceJob;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->seedServiceJobs();
            $this->seedParts();
            $this->seedMechanics();
            $this->seedCustomersAndVehicles();
            $this->seedUsers();
        });
    }

    private function seedServiceJobs(): void
    {
        $jobs = [
            [
                'id_job' => '00000BL063',
                'kode_motor' => 'BL',
                'keterangan' => 'Complete Service (CS/PL) BEAT SPORTY DLX',
                'harga' => 55000,
                'is_active' => true,
            ],
            [
                'id_job' => '00001VP063',
                'kode_motor' => 'VP',
                'keterangan' => 'SERVIS LENGKAP VARIO 125',
                'harga' => 55000,
                'is_active' => true,
            ],
            [
                'id_job' => '00003VP071',
                'kode_motor' => 'VP',
                'keterangan' => 'Jasa Ganti Ban',
                'harga' => 30000,
                'is_active' => true,
            ],
            [
                'id_job' => '00000BL111',
                'kode_motor' => 'BL',
                'keterangan' => 'Jasa Ganti Oli',
                'harga' => 10000,
                'is_active' => true,
            ],
            [
                'id_job' => '00001VP061',
                'kode_motor' => 'VP',
                'keterangan' => 'Ganti Kanvas Rem Belakang',
                'harga' => 25000,
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $job) {
            ServiceJob::updateOrCreate(
                ['id_job' => $job['id_job']],
                $job
            );
        }
    }

    private function seedParts(): void
    {
        $parts = [
            [
                'part_number' => '42711K59A12',
                'nama_part' => 'TIRE RR TL (90/90-14) - Ban Vario',
                'harga' => 290000,
                'qty_stock' => 11,
                'qty_rfs' => 11,
                'qty_book' => 0,
                'is_active' => true,
            ],
            [
                'part_number' => '43130KZL930',
                'nama_part' => 'BRAKE SHOE (Kanvas Rem)',
                'harga' => 55000,
                'qty_stock' => 14,
                'qty_rfs' => 14,
                'qty_book' => 0,
                'is_active' => true,
            ],
            [
                'part_number' => '082342MBK0LZ0',
                'nama_part' => 'Oli SPX2 10W30 SLMB 0,8L REP',
                'harga' => 73500,
                'qty_stock' => 9,
                'qty_rfs' => 9,
                'qty_book' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($parts as $part) {
            Part::updateOrCreate(
                ['part_number' => $part['part_number']],
                $part
            );
        }
    }

    private function seedMechanics(): void
    {
        $mechanics = [
            [
                'id_mekanik' => 'MEK/68601/00001',
                'honda_id_mekanik' => '99999',
                'nama_mekanik' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'status_aktif' => true,
            ],
            [
                'id_mekanik' => 'MEK/68601/00002',
                'honda_id_mekanik' => '88888',
                'nama_mekanik' => 'Andi Saputra',
                'no_hp' => '081298765432',
                'status_aktif' => true,
            ],
        ];

        foreach ($mechanics as $mechanic) {
            Mechanic::updateOrCreate(
                ['id_mekanik' => $mechanic['id_mekanik']],
                $mechanic
            );
        }
    }

    private function seedCustomersAndVehicles(): void
    {
        $customer1 = Customer::updateOrCreate(
            ['id_customer' => 'CUST/TEST/12345'],
            [
                'nama_customer' => 'Ahmad Fauzi',
                'no_hp' => '081311112222',
                'email' => 'ahmad@example.com',
                'no_identitas' => '1371010101010001',
                'alamat' => 'Kota Padang, Sumatera Barat',
            ]
        );

        $customer2 = Customer::updateOrCreate(
            ['id_customer' => 'CUST/TEST/12346'],
            [
                'nama_customer' => 'Rina Marlina',
                'no_hp' => '081322223333',
                'email' => 'rina@example.com',
                'no_identitas' => '1371010101010002',
                'alamat' => 'Kota Padang, Sumatera Barat',
            ]
        );

        Vehicle::updateOrCreate(
            ['no_plat' => 'BA 1234 AA'],
            [
                'id_customer' => $customer1->id_customer,
                'kode_motor' => 'BL',
                'nama_unit' => 'Honda Beat Sporty',
                'tahun' => 2023,
                'no_rangka' => 'MH1TEST0000000001',
                'no_mesin' => 'JMTEST000000001',
            ]
        );

        Vehicle::updateOrCreate(
            ['no_plat' => 'BA 5678 BB'],
            [
                'id_customer' => $customer2->id_customer,
                'kode_motor' => 'VP',
                'nama_unit' => 'Honda Vario 125',
                'tahun' => 2022,
                'no_rangka' => 'MH1TEST0000000002',
                'no_mesin' => 'JMTEST000000002',
            ]
        );
    }

    private function seedUsers(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@ahass.test'],
            [
                'name' => 'Administrator AHASS',
                'password' => Hash::make('password'),
            ]
        );
    }
}
