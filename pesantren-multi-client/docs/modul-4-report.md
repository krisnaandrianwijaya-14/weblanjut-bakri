# Modul 4 — Isolasi Multi-Client

## Komponen

### 1. Middleware `SetClientContext`
Lokasi: `app/Http/Middleware/SetClientContext.php`

Fungsi:
- Resolve client dari route parameter `{client:slug}`
- Cek client aktif (status = 'active')
- Verifikasi user adalah anggota client (via `client_users`)
- Set `ClientContext` (scoped binding)
- Clear context di `finally` block

### 2. Trait `BelongsToClient`
Lokasi: `app/Models/Concerns/BelongsToClient.php`

Fungsi:
- Global scope otomatis filter query dengan `client_id`
- Event `creating` auto-fill `client_id` dari context

### 3. Policy Classes
- `StudentPolicy` — view, create, update, delete
- `BillPolicy` — role-based access
- `LeaveRequestPolicy` — view, create, activate, report
- `AttendancePolicy` — view, create

### 4. Scoped Route Binding
Diterapkan pada grup route `client/{client:slug}/*` dengan `->scopeBindings()`.

## Hasil Test

### Security Regression Tests
