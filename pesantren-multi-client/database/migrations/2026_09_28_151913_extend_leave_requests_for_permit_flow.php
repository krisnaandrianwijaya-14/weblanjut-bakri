<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ============ 1. Tambah kolom baru ============
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->timestamp('deadline')->nullable()->after('end_time');
            $table->timestamp('departed_at')->nullable()->after('deadline');
            $table->timestamp('returned_at')->nullable()->after('departed_at');

            $table->boolean('is_late')->default(false)->after('returned_at');
            $table->integer('late_minutes')->nullable()->after('is_late');

            $table->unsignedBigInteger('issued_by')->nullable()->after('late_minutes');
            $table->unsignedBigInteger('activated_by')->nullable()->after('issued_by');
            $table->unsignedBigInteger('completed_by')->nullable()->after('activated_by');
        });

        // ============ 2. Ubah ENUM status (MySQL/MariaDB only) ============
        // SQLite tidak support ALTER TABLE MODIFY, skip untuk driver lain
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE leave_requests MODIFY status ENUM(
                'draft', 'menunggu_ttd_offline', 'izin_aktif',
                'kembali_selesai', 'dibatalkan'
            ) DEFAULT 'menunggu_ttd_offline'");
        } else {
            // SQLite & driver lain: pakai string column tanpa ENUM constraint
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->string('status', 30)->default('menunggu_ttd_offline')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE leave_requests MODIFY status ENUM(
                'draft', 'submitted', 'under_review', 'approved', 'rejected', 'cancelled'
            ) DEFAULT 'submitted'");
        }

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn([
                'deadline', 'departed_at', 'returned_at',
                'is_late', 'late_minutes',
                'issued_by', 'activated_by', 'completed_by',
            ]);
        });
    }
};
