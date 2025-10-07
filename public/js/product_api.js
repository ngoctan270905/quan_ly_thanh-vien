document.addEventListener('DOMContentLoaded', function() {
    const productListContainer = document.getElementById('product-list');
    const API_URL = 'http://127.0.0.1:8000/api/products'; 

    // Lấy CSRF token (cần cho POST/PUT/DELETE, nhưng nên lấy sẵn)
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // 💡 KHÔNG CẦN DÙNG AUTH_TOKEN nữa
    // const AUTH_TOKEN = 'Ld1krgpWUfZMTfJVKiG4BpPp2JPAX18mwJluN5Tp79bdff22'; 

    fetch(API_URL, {
        method: 'GET',
        credentials: 'include', // Gửi cookie (nếu có) cùng yêu cầu
        // 💡 Bỏ header Authorization
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest', // Quan trọng cho Laravel nhận diện AJAX

            // Nếu đây là POST/PUT/DELETE, bạn sẽ thêm header này:
            // 'X-CSRF-TOKEN': csrfToken 
        }
    })
    .then(response => {
        // Xử lý lỗi HTTP (ví dụ: 401 Unauthorized, 404 Not Found)
        if (response.status === 401 || response.status === 403) {
             // Nếu chưa đăng nhập hoặc không có quyền
             productListContainer.innerHTML = '<p style="color: red;">Truy cập bị từ chối. Vui lòng đăng nhập.</p>';
             // Có thể chuyển hướng người dùng đến trang đăng nhập ở đây
             // window.location.href = '/login'; 
             throw new Error('Unauthenticated or Unauthorized');
        }
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json(); // Chuyển đổi phản hồi thành JSON
    })
    .then(products => {
        productListContainer.innerHTML = ''; 
        
        if (products.length === 0) {
            productListContainer.innerHTML = '<p>Không có sản phẩm nào.</p>';
            return;
        }

        const ul = document.createElement('ul');
        products.forEach(product => {
            const li = document.createElement('li');
            li.innerHTML = `
                <strong>${product.name}</strong> - 
                Giá: $${product.price} - 
                Mô tả: ${product.description}
            `;
            ul.appendChild(li);
        });
        productListContainer.appendChild(ul);
    })
    .catch(error => {
        console.error('Lỗi khi fetch dữ liệu:', error);
        productListContainer.innerHTML = `<p style="color: red;">Lỗi tải dữ liệu. Vui lòng kiểm tra console. (${error.message})</p>`;
    });
});