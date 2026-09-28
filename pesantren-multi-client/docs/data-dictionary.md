# Data Dictionary — Pesantren Multi-Client

## Scope

| Kategori | Tabel |
|---|---|
| **Platform** | clients, users, roles, audit_logs, client_modules |
| **Client-scoped** | Semua tabel dengan `client_id` |

## Aturan Umum

- Semua tabel client-scoped wajib punya kolom `client_id`.
- Composite FK: `(client_id, x_id) → table(client_id, id)`.
- Tabel **append-only**: `permit_violations`, `audit_logs`, `attendances`.
- Uang disimpan sebagai `DECIMAL(15,2)`, bukan FLOAT.

## Tabel Utama

### `clients` (Platform)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| name | VARCHAR(120) | Nama pesantren |
| slug | VARCHAR(100) UNIQUE | URL slug |
| status | ENUM | pending/active/suspended/disabled |

### `students` (Client-scoped)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | Composite (client_id, id) unique |
| name | VARCHAR(120) | Nama lengkap |
| student_number | VARCHAR(30) | NIS (unique per client) |
| qr_token | VARCHAR(64) UNIQUE | QR statis KTS |
| qr_image_path | VARCHAR | Path file QR |
| presence_status | ENUM | inside / outside |
| last_check_in_at | TIMESTAMP | Waktu masuk terakhir |
| last_check_out_at | TIMESTAMP | Waktu keluar terakhir |

### `leave_requests` (Client-scoped)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | |
| student_id | BIGINT | Composite FK |
| code | VARCHAR(40) UNIQUE | Nomor surat (IZN-YYYYMMDD-XXXX) |
| type | ENUM | pulang/keluar/sakit/lainnya |
| deadline | TIMESTAMP | Batas waktu kembali |
| departed_at | TIMESTAMP | Waktu keluar aktual |
| returned_at | TIMESTAMP | Waktu kembali aktual |
| is_late | BOOLEAN | Flag pelanggaran |
| late_minutes | INT | Menit keterlambatan |
| issued_by | BIGINT | User yang menerbitkan |
| activated_by | BIGINT | User yang validasi keluar |
| completed_by | BIGINT | User yang validasi kembali |
| status | ENUM | menunggu_ttd_offline → izin_aktif → kembali_selesai |

### `permit_violations` (APPEND-ONLY)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | |
| student_id | BIGINT FK | |
| leave_request_id | BIGINT FK | |
| late_minutes | INT | Menit terlambat (positif) |
| notes | TEXT | Deskripsi |
| recorded_at | TIMESTAMP | Waktu pencatatan |
| recorded_by | BIGINT | User yang mencatat |

### `attendances` (APPEND-ONLY)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | |
| student_id | BIGINT | |
| type | ENUM | academic/dormitory/gate |
| status | ENUM | present/late/absent/permission/sick |
| direction | ENUM | in / out |
| device_id | VARCHAR(64) | ID scanner |
| scanned_at | TIMESTAMP | Waktu scan |

### `bills` (Client-scoped)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | |
| student_id | BIGINT FK | |
| amount | DECIMAL(15,2) | Jumlah tagihan |
| period | VARCHAR(20) | Periode (YYYY-MM) |
| status | ENUM | draft/issued/partially_paid/paid/overdue/cancelled |

### `payments` (Client-scoped)
| Kolom | Tipe | Keterangan |
|---|---|---|
| id | BIGINT PK | |
| client_id | BIGINT FK | |
| bill_id | BIGINT FK | |
| order_id | VARCHAR(64) UNIQUE | ID dari payment gateway |
| snap_token | VARCHAR | Token Midtrans |
| amount | DECIMAL(15,2) | Jumlah |
| paid_amount | DECIMAL(15,2) | Jumlah dibayar |
| status | ENUM | initiated/pending/recorded/reversed/expired/failed |
| recorded_at | TIMESTAMP | Waktu tercatat |

## Composite FK Penting

| Tabel | FK | Referensi |
|---|---|---|
| students | (client_id, id) | UNIQUE INDEX untuk rujukan |
| student_guardians | (client_id, student_id) | students(client_id, id) |
| class_student | (client_id, class_id) | classes(client_id, id) |
| grades | (client_id, student_id) | students(client_id, id) |
| bills | (client_id, student_id) | students(client_id, id) |
| payments | (client_id, bill_id) | bills(client_id, id) |
| leave_requests | (client_id, student_id) | students(client_id, id) |
| permit_violations | (client_id, student_id) | students(client_id, id) |

## Data Demo

Setelah `migrate:fresh --seed`:
- 2 clients: Al-Hikmah, Al-Falah
- 21 santri Client A + 1 santri Client B
- 5 guru, 5 wali, 4 kelas, 5 mapel
- 5 kamar, 10 tagihan, ~4 pembayaran
- QR statis: `SANTRI-2026-A001-XYZ123` (Ahmad Fauzan)
