# 📚 Hướng dẫn Seed Dữ liệu Gia Phả

## 🎯 Tổng quan

Dự án có 2 seeder để tạo dữ liệu mẫu cho gia phả:

### 1. **FamilySeeder** (Nhỏ gọn)
- **Số lượng:** ~30 người
- **Thế hệ:** 5 thế hệ
- **Mục đích:** Demo nhanh, test UI cơ bản

### 2. **FamilyTreeSeeder** (Toàn diện) ⭐
- **Số lượng:** 100 người
- **Thế hệ:** 8 thế hệ
- **Mục đích:** Test hiệu năng, demo đầy đủ tính năng
- **Cấu trúc:**
  - Thế hệ 1: 1 người (Cụ tổ)
  - Thế hệ 2: 4 người
  - Thế hệ 3: 8 người
  - Thế hệ 4: 16 người
  - Thế hệ 5: 20 người
  - Thế hệ 6: 20 người
  - Thế hệ 7: 21 người
  - Thế hệ 8: 10 người (thế hệ hiện tại)

## 🚀 Cách sử dụng

### Bước 1: Chọn Seeder

Mở file `database/seeders/DatabaseSeeder.php` và uncomment seeder bạn muốn dùng:

```php
$this->call([
    // FamilySeeder::class,           // Seeder nhỏ
    FamilyTreeSeeder::class,          // Seeder lớn (100 người)
]);
```

### Bước 2: Reset và Seed Database

#### Option A: Reset toàn bộ + Seed mới (Khuyến nghị)
```bash
php artisan migrate:fresh --seed
```

#### Option B: Chỉ chạy Seeder (giữ dữ liệu cũ)
```bash
php artisan db:seed
```

#### Option C: Chạy một Seeder cụ thể
```bash
# Seeder lớn
php artisan db:seed --class=FamilyTreeSeeder

# Seeder nhỏ
php artisan db:seed --class=FamilySeeder
```

## 📊 Cấu trúc Dữ liệu

### File nguồn
- **Data:** `database/seeders/data/family_tree_data.php`
- **Seeder:** `database/seeders/FamilyTreeSeeder.php`

### Cấu trúc mỗi người
```php
[
    'id' => 1,                          // ID trong data file
    'name' => 'Nguyễn Văn Tổ',         // Tên đầy đủ
    'generation' => 1,                  // Thế hệ
    'gender' => 'male',                 // Giới tính: male/female
    'birth_year' => 1820,               // Năm sinh
    'death_year' => 1895,               // Năm mất (null = còn sống)
    'parent_id' => null,                // ID cha/mẹ (null = cụ tổ)
    'spouse_name' => 'Trần Thị Hạnh',   // Tên vợ/chồng
    'notes' => 'Cụ tổ đầu tiên',        // Ghi chú
]
```

### Mapping sang Model Person
- `birth_year` → `date_of_birth` (format: YYYY-01-01)
- `death_year` → `date_of_death` (format: YYYY-01-01 hoặc null)
- `death_year` → `is_alive` (null = true, có giá trị = false)
- `parent_id` + `gender` → `father_id` hoặc `mother_id`
- `notes` → `nickname`

## ⚙️ Tùy chỉnh Dữ liệu

### Thêm/Sửa người trong gia phả

Chỉnh sửa file: `database/seeders/data/family_tree_data.php`

```php
// Thêm người mới
[
    'id' => 101,                        // ID mới (unique)
    'name' => 'Nguyễn Văn Mới',
    'generation' => 8,
    'gender' => 'male',
    'birth_year' => 2002,
    'death_year' => null,               // Còn sống
    'parent_id' => 71,                  // ID của cha/mẹ
    'spouse_name' => null,              // Chưa kết hôn
    'notes' => 'Sinh viên',
],
```

### Lưu ý quan trọng

1. **ID phải unique** trong file data
2. **parent_id** phải tham chiếu đến ID đã tồn tại
3. **gender** của parent xác định `father_id` hoặc `mother_id`
4. **birth_year** phải nhỏ hơn `death_year` (nếu có)
5. Cụ tổ có `parent_id = null`

## 🧪 Testing

Sau khi seed, kiểm tra:

```bash
# Đếm tổng số người
php artisan tinker
>>> App\Models\Person::count()
=> 100

# Kiểm tra cụ tổ
>>> App\Models\Person::whereNull('father_id')->whereNull('mother_id')->first()

# Kiểm tra thế hệ 8
>>> App\Models\Person::where('date_of_birth', '>=', '1995-01-01')->count()
```

## 🔧 Troubleshooting

### Lỗi: "Class FamilyTreeSeeder not found"
```bash
composer dump-autoload
```

### Lỗi: Foreign key constraint fails
- Đảm bảo chạy `migrate:fresh --seed` để reset database
- Kiểm tra `parent_id` trong data file có hợp lệ

### Lỗi: Duplicate entry
- Reset database: `php artisan migrate:fresh --seed`
- Hoặc xóa dữ liệu cũ trước: `Person::truncate()`

## 📝 Notes

- Seeder tự động map relationship dựa trên gender của parent
- Tên vợ/chồng (`spouse_name`) hiện tại chỉ lưu trong `notes`, chưa tạo Person record riêng
- Nếu cần tạo Person record cho vợ/chồng, cần customize seeder
