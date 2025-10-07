<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sản Phẩm Mới</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <style>
        .error { color: red; }
    </style>
</head>
<body>

    <a href="{{ route('products.index') }}">← Quay lại Danh Sách Sản Phẩm</a>
    <h1>Thêm Sản Phẩm Mới</h1>

    <form id="product-form">
        <div>
            <label for="name">Tên Sản Phẩm:</label>
            <input type="text" id="name" required>
            <div id="name-error" class="error"></div>
        </div>
        <div>
            <label for="description">Mô Tả:</label>
            <textarea id="description"></textarea>
        </div>
        <div>
            <label for="price">Giá:</label>
            <input type="number" id="price" required min="0">
            <div id="price-error" class="error"></div>
        </div>
        <div>
            <label for="image_url">URL Hình Ảnh:</label>
            <input type="url" id="image_url">
        </div>
        <button type="submit">Thêm Sản Phẩm</button>
        <p id="message-status"></p>
    </form>

    <script src="{{ asset('js/product_create.js') }}"></script> 
</body>
</html>