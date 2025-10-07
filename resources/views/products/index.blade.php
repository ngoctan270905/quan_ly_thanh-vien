<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh Sách Sản Phẩm API</title>
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
</head>
<body>

    <div style="margin-bottom: 20px;">
        <a href="{{ route('products.create') }}" style="
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        ">
            Thêm Sản Phẩm
        </a>
    </div>

    <h1>Danh Sách Sản Phẩm</h1>

    <div id="product-list">
        <p>Đang tải dữ liệu...</p>
    </div>

    <script src="{{ asset('js/product_api.js') }}"></script>
</body>
</html>