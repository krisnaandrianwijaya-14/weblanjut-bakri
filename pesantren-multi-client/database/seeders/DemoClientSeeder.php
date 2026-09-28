<?php

namespace Database\Seeders;

use App\Models\{
    Client, ClientUser, User, Student, AttendanceDevice,
    Role, ClientModule, Guardian, StudentGuardian, Teacher,
    SchoolClass, Subject, Schedule, Grade,
    Dormitory, Room, RoomAssignment,
    Bill, Payment, Announcement,
};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoClientSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================================
        // PART 1 — PLATFORM & CLIENT
        // =====================================================

        // 1. Platform Admin
        User::updateOrCreate(
            ['email' => 'platform@pesantren.test'],
            [
                'name'     => 'Super Admin Platform',
                'password' => Hash::make('password'),
                'role'     => 'platform_admin',
                'status'   => 'active',
            ]
        );

        // 2. Client A
        $clientA = Client::updateOrCreate(
            ['slug' => 'al-hikmah'],
            [
                'name'    => 'Pesantren Al-Hikmah',
                'status'  => 'active',
                'address' => 'Surabaya, Jawa Timur',
                'email'   => 'info@alhikmah.test',
                'phone'   => '031-1234567',
                'nsp'     => '510032740001',
            ]
        );

        // 3. Client B
        $clientB = Client::updateOrCreate(
            ['slug' => 'al-falah'],
            [
                'name'    => 'Pesantren Al-Falah',
                'status'  => 'active',
                'address' => 'Kediri, Jawa Timur',
                'email'   => 'info@alfalah.test',
                'phone'   => '0354-7654321',
                'nsp'     => '510032740002',
            ]
        );

        // 4. Admin A
        $adminA = User::updateOrCreate(
            ['email' => 'admin@alhikmah.test'],
            [
                'name'     => 'Admin Al-Hikmah',
                'password' => Hash::make('password'),
                'role'     => 'admin_pesantren',
                'status'   => 'active',
            ]
        );

        ClientUser::updateOrCreate(
            ['client_id' => $clientA->id, 'user_id' => $adminA->id],
            ['role' => 'admin_pesantren', 'is_active' => true]
        );

        // 5. Admin B
        $adminB = User::updateOrCreate(
            ['email' => 'admin@alfalah.test'],
            [
                'name'     => 'Admin Al-Falah',
                'password' => Hash::make('password'),
                'role'     => 'admin_pesantren',
                'status'   => 'active',
            ]
        );

        ClientUser::updateOrCreate(
            ['client_id' => $clientB->id, 'user_id' => $adminB->id],
            ['role' => 'admin_pesantren', 'is_active' => true]
        );

        // 6. Device untuk Client A
        $device = AttendanceDevice::updateOrCreate(
            ['device_id' => 'DEV-ALHIKMAH-01'],
            [
                'client_id'     => $clientA->id,
                'name'          => 'Scanner Asrama Al-Ghazali',
                'location_type' => 'dormitory',
                'location_id'   => 1,
                'api_key'       => 'secret-key-alhikmah-12345',
                'is_active'     => true,
            ]
        );

        // 7. Santri Client A
        $studentA = Student::updateOrCreate(
            ['client_id' => $clientA->id, 'student_number' => '2026.0401'],
            [
                'name'            => 'Ahmad Fauzan',
                'qr_token'        => 'SANTRI-2026-A001-XYZ123',
                'status'          => 'active',
                'presence_status' => 'inside',
            ]
        );

        // 8. Santri Client B
        $studentB = Student::updateOrCreate(
            ['client_id' => $clientB->id, 'student_number' => '2026.0401'],
            [
                'name'            => 'Muhammad Rizky',
                'qr_token'        => 'SANTRI-2026-B001-ABC456',
                'status'          => 'active',
                'presence_status' => 'inside',
            ]
        );

// ===== 8b. Tambah 20 santri dummy Client A =====
$namaSantri = [
    'Muhammad Rizky A', 'Zulfa Ainun Najib', 'Abdullah Hakim',
    'Fatimah Az-Zahra', 'Umar bin Khattab', 'Aisyah Nur',
    'Ali bin Abi Thalib', 'Khadijah Salsabila', 'Hamzah bin Abdul',
    'Bilal bin Rabah', 'Salman Al-Farisi', 'Abu Bakar Ash-Shiddiq',
    'Utsman bin Affan', 'Zaid bin Tsabit', 'Khalid bin Walid',
    "Sa'ad bin Abi Waqqas", 'Thalhah bin Ubaidillah', 'Zubair bin Awwam',
    'Abdurrahman bin Auf', 'Umar bin Abdul Aziz',
];

foreach ($namaSantri as $i => $nama) {
    Student::updateOrCreate(
        [
            'client_id'      => $clientA->id,
            'student_number' => '2026.' . str_pad($i + 2, 4, '0', STR_PAD_LEFT),
        ],
        [
            'name'            => $nama,
            'qr_token'        => 'SANTRI-2026-A' . str_pad($i + 2, 3, '0', STR_PAD_LEFT) . '-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'status'          => 'active',
            'presence_status' => 'inside',
        ]
    );
}

$this->command->info('✅ 20 santri dummy Client A dibuat.');

        // =====================================================
        // PART 2 — DATA CLIENT A (EXPLICIT client_id)
        // =====================================================

        // 9. Roles
        foreach ([
            'platform_admin'  => 'Platform Admin',
            'admin_pesantren' => 'Admin Pesantren',
            'guru'            => 'Guru/Pengajar',
            'wali_asrama'     => 'Wali Asrama',
            'wali_santri'     => 'Wali Santri',
            'santri'          => 'Santri',
        ] as $name => $label) {
            Role::updateOrCreate(['name' => $name], ['label' => $label]);
        }

        // 10. Client Modules
        foreach (['academic', 'dormitory', 'finance', 'leave', 'announcement'] as $mod) {
            ClientModule::updateOrCreate(
                ['client_id' => $clientA->id, 'module_name' => $mod],
                ['is_enabled' => true, 'enabled_at' => now()]
            );
        }

        // 11. Guardians — explicit client_id
        $guardians = collect();
        for ($i = 0; $i < 5; $i++) {
            $guardians->push(Guardian::create([
                'client_id'  => $clientA->id,
                'name'       => fake()->name(),
                'relation'   => fake()->randomElement(['ayah', 'ibu', 'wali']),
                'phone'      => '08' . fake()->numerify('##########'),
                'email'      => fake()->safeEmail(),
                'address'    => fake()->address(),
                'occupation' => fake()->jobTitle(),
            ]));
        }

        // 12. Teachers — explicit client_id
        $teachers = collect();
        for ($i = 0; $i < 5; $i++) {
            $teachers->push(Teacher::create([
                'client_id'       => $clientA->id,
                'name'            => 'Ust. ' . fake()->name(),
                'employee_number' => 'GURU-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                'phone'           => '08' . fake()->numerify('##########'),
                'status'          => 'active',
            ]));
        }

        // 13. Classes — explicit client_id
        $classes = collect();
        foreach (['Ula 1A', 'Ula 1B', 'Wustho 2A', 'Wustho 2B'] as $name) {
            $classes->push(SchoolClass::create([
                'client_id'           => $clientA->id,
                'name'                => $name,
                'academic_year'       => '2025/2026',
                'level'               => explode(' ', $name)[0],
                'homeroom_teacher_id' => $teachers->random()->id,
            ]));
        }

        // 14. Subjects — explicit client_id
        $subjects = collect();
        foreach (['Fiqih', 'Nahwu', 'Tauhid', 'Tafsir', 'Hadits'] as $i => $name) {
            $subjects->push(Subject::create([
                'client_id'  => $clientA->id,
                'code'       => 'MP-0' . ($i + 1),
                'name'       => $name,
                'teacher_id' => $teachers->random()->id,
            ]));
        }

        // 15. Enroll Students & Assign Guardian
        $studentsA = Student::where('client_id', $clientA->id)->get();

        foreach ($studentsA as $student) {
            DB::table('class_student')->insert([
                'client_id'     => $clientA->id,
                'class_id'      => $classes->random()->id,
                'student_id'    => $student->id,
                'academic_year' => '2025/2026',
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            StudentGuardian::create([
                'client_id'   => $clientA->id,
                'student_id'  => $student->id,
                'guardian_id' => $guardians->random()->id,
                'is_primary'  => true,
            ]);
        }

        // 16. Schedules — explicit client_id
        foreach ($classes as $class) {
            foreach (['senin', 'selasa', 'rabu'] as $day) {
                Schedule::create([
                    'client_id'   => $clientA->id,
                    'class_id'    => $class->id,
                    'subject_id'  => $subjects->random()->id,
                    'teacher_id'  => $teachers->random()->id,
                    'day_of_week' => $day,
                    'start_time'  => '07:30',
                    'end_time'    => '09:00',
                    'room'        => 'R-' . rand(1, 5),
                ]);
            }
        }

        // 17. Grades — explicit client_id
        foreach ($studentsA->take(5) as $student) {
            foreach (['tugas', 'uts', 'uas'] as $component) {
                Grade::create([
                    'client_id'     => $clientA->id,
                    'student_id'    => $student->id,
                    'subject_id'    => $subjects->random()->id,
                    'teacher_id'    => $teachers->random()->id,
                    'academic_year' => '2025/2026',
                    'component'     => $component,
                    'score'         => rand(70, 95),
                ]);
            }
        }

        // 18. Dormitory & Rooms — explicit client_id
        $dorm = Dormitory::create([
            'client_id'     => $clientA->id,
            'name'          => 'Gedung Al-Ghazali',
            'gender'        => 'putra',
            'location'      => 'Sisi Timur',
            'supervisor_id' => $adminA->id,
        ]);

        $rooms = collect();
        foreach (['K-01', 'K-02', 'K-03', 'K-04', 'K-05'] as $num) {
            $rooms->push(Room::create([
                'client_id'    => $clientA->id,
                'dormitory_id' => $dorm->id,
                'number'       => $num,
                'capacity'     => 8,
            ]));
        }

        // 19. Room Assignments — explicit client_id
        foreach ($studentsA->take(15) as $i => $student) {
            RoomAssignment::create([
                'client_id'  => $clientA->id,
                'room_id'    => $rooms[$i % 5]->id,
                'student_id' => $student->id,
                'start_date' => now()->subMonth(),
                'is_active'  => true,
            ]);
            $rooms[$i % 5]->increment('occupied');
        }

        // 20. Bills & Payments — explicit client_id
        foreach ($studentsA->take(10) as $student) {
            $bill = Bill::create([
                'client_id'   => $clientA->id,
                'student_id'  => $student->id,
                'description' => 'Syahriah SPP September 2026',
                'amount'      => 350000,
                'period'      => '2026-09',
                'due_date'    => now()->addDays(10),
                'status'      => 'issued',
            ]);

            if (rand(0, 1)) {
                Payment::create([
                    'client_id'    => $clientA->id,
                    'bill_id'      => $bill->id,
                    'order_id'     => 'INV-' . now()->format('YmdHis') . '-' . $bill->id,
                    'amount'       => 350000,
                    'paid_amount'  => 350000,
                    'payment_type' => 'qris',
                    'status'       => 'recorded',
                    'recorded_at'  => now(),
                ]);
                $bill->update(['status' => 'paid']);
            }
        }

        // 21. Announcement — explicit client_id
        Announcement::create([
            'client_id'       => $clientA->id,
            'title'           => 'Libur Menyambut Ramadhan 1447 H',
            'body'            => 'Diberitahukan kepada seluruh santri bahwa libur Ramadhan dimulai H-7 Idul Fitri.',
            'target_audience' => 'all',
            'status'          => 'published',
            'published_at'    => now(),
            'author_id'       => $adminA->id,
        ]);

        // =====================================================
        // PART 3 — SUMMARY
        // =====================================================
        $this->command->info('');
        $this->command->info('✅ Demo Seeder Selesai!');
        $this->command->info('─────────────────────────────────────────────');
        $this->command->info('Platform Admin:');
        $this->command->info('   platform@pesantren.test / password');
        $this->command->info('');
        $this->command->info('Client A — Pesantren Al-Hikmah:');
        $this->command->info('   admin@alhikmah.test / password');
        $this->command->info('   Device ID: ' . $device->device_id);
        $this->command->info('   QR Santri A: ' . $studentA->qr_token);
        $this->command->info('');
        $this->command->info('Client B — Pesantren Al-Falah:');
        $this->command->info('   admin@alfalah.test / password');
        $this->command->info('   QR Santri B: ' . $studentB->qr_token);
        $this->command->info('─────────────────────────────────────────────');
        $this->command->info('Data Client A:');
        $this->command->info('   Guardians: ' . Guardian::count());
        $this->command->info('   Students: ' . Student::where('client_id', $clientA->id)->count());
        $this->command->info('   Teachers: ' . Teacher::count());
        $this->command->info('   Classes: ' . SchoolClass::count());
        $this->command->info('   Subjects: ' . Subject::count());
        $this->command->info('   Schedules: ' . Schedule::count());
        $this->command->info('   Grades: ' . Grade::count());
        $this->command->info('   Rooms: ' . Room::count());
        $this->command->info('   Bills: ' . Bill::count());
        $this->command->info('   Payments: ' . Payment::count());
        $this->command->info('   Announcements: ' . Announcement::count());
        $this->command->info('═════════════════════════════════════════════');
    }
}
