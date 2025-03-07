# Todo API Documentation

## 1. Import Postman Collection

Untuk mempermudah pengujian API, kamu bisa mengimpor file koleksi Postman:

1. Buka aplikasi Postman.
2. Klik tombol **Import** di pojok kiri atas.
3. Pilih file JSON koleksi Postman yang disediakan.
4. Klik **Confirm** untuk menyelesaikan impor.

Sekarang kamu bisa langsung mengakses semua endpoint yang tersedia!

---

## 2. Cara Penggunaan: Store Todo

**Endpoint:**
```
POST /api/todos/create
```

**Request Body:**
```json
{
    "title": "Nama Tugas",
    "assignee": "Nama Assignee",
    "due_date": "2025-03-10",
    "time_tracked": 5,
    "status": "pending",
    "priority": "high"
}
```

**Response:**
```json
{
    "code": 201,
    "message": "Todo berhasil ditambahkan",
    "data": {
        "title": "Nama Tugas",
        "assignee": "Nama Assignee",
        "due_date": "2025-03-10",
        "time_tracked": 5,
        "status": "pending",
        "priority": "high"
    },
    "error": null,
    "timestamp": "2025-03-07T18:54:25.409752Z"
}
```

---

## 3. Cara Penggunaan: Export Excel

**Endpoint:**
```
GET /api/todos/export
```

**Response:**
- File Excel akan otomatis terunduh.
- Pastikan browser kamu mengizinkan unduhan file.

---

## 4. Cara Penggunaan: Get Chart Data

**Endpoint:**
```
GET /api/todos/chart?type={type}
```

**Parameter:**
- **type**: Jenis data chart yang ingin diambil.
    - `status`: untuk ringkasan status todo.
    - `priority`: untuk ringkasan prioritas todo.
    - `assignee`: untuk ringkasan assignee todo.

**Contoh Request:**
```
GET /api/todos/chart?type=status
```

**Response:**
```json
{
    "code": 200,
    "message": "Success",
    "data": {
        "status_summary": {
            "completed": 3,
            "in_progress": 2,
            "open": 10,
            "pending": 2
        }
    },
    "error": null,
    "timestamp": "2025-03-07T18:54:25.409752Z"
}
```

---

Selamat mencoba! 🚀

