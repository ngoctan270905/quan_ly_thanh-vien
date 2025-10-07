# Laravel Assignment – API & SPA Integration (Vue.js / React)

## 🧩 Bối cảnh

Hệ thống quản lý sản phẩm (Product Management System) dưới dạng SPA.

* Backend: **Laravel API**
* Frontend: **Vue.js** hoặc **React**
* Xác thực: **Laravel Sanctum** (session/cookie-based)

---

## 🔌 Backend – Laravel API & Sanctum

* Tạo dự án Laravel mới: `product-api`
* Cài đặt Sanctum:

```bash
composer require laravel/sanctum
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

* Cấu hình middleware trong `api.php` và `config/sanctum.php` để SPA gửi cookie (`withCredentials: true`)

### 📦 API Product CRUD

* Model: `Product` với các field:

  * `name` (string, required, unique)
  * `description` (text, optional)
  * `price` (decimal, required, >=0)
  * `image_url` (string/url, optional)
* Endpoints `/api/products` (tất cả bảo vệ bởi `auth:sanctum`):

  | Method | Endpoint           | Action             |
  | ------ | ------------------ | ------------------ |
  | GET    | /api/products      | Danh sách sản phẩm |
  | POST   | /api/products      | Tạo mới sản phẩm   |
  | GET    | /api/products/{id} | Chi tiết sản phẩm  |
  | PUT    | /api/products/{id} | Cập nhật sản phẩm  |
  | DELETE | /api/products/{id} | Xóa sản phẩm       |

### 🧠 Bonus

* Laravel HTTP Client để gọi API ngoài (`https://fakestoreapi.com/products`)
* Command `php artisan import:products` để import dữ liệu mẫu

---

## 💻 Frontend SPA (Vue.js / React)

* Cấu trúc: `/resources/js/components/`
* Sử dụng **axios** + **vue-router** / **react-router-dom**
* Tất cả request API gửi `withCredentials: true`
* Các trang chính:

  | Trang          | Chức năng                                 |
  | -------------- | ----------------------------------------- |
  | Login          | Gửi POST /login, xử lý session/token      |
  | Product List   | Gọi GET /api/products, hiển thị danh sách |
  | Product Detail | Hiển thị chi tiết sản phẩm                |
  | Product Form   | Tạo/Sửa sản phẩm, upload ảnh (optional)   |

---

## 🧪 Kiểm thử & Kỳ vọng

* Xác thực hoạt động đúng (login/logout, token/cookie)
* CRUD sản phẩm trơn tru từ frontend → backend
* Vue/React tương tác mượt mà với Laravel API
* Axios gửi đúng credentials, tránh lỗi CORS
* Frontend xử lý form, xác thực, lỗi API
* Laravel HTTP Client hoạt động tốt cho API ngoài

---

## 💡 Mở rộng nâng cao

* Search/filter sản phẩm theo tên, giá
* Pagination cho API GET /products
* Quản lý state: Vuex / Pinia / Redux
* Module phân quyền: chỉ admin có thể tạo/sửa/xóa sản phẩm
