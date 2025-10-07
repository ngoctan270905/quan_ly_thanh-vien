document.addEventListener('DOMContentLoaded', function() {
    const API_URL = 'http://127.0.0.1:8000/api/products'; 
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const productForm = document.getElementById('product-form');
    const messageStatus = document.getElementById('message-status');

    // Hàm chung để hiển thị lỗi Validation từ Laravel (422)
    function displayValidationErrors(errors) {
        document.querySelectorAll('.error').forEach(el => el.textContent = '');
        for (const field in errors) {
            const errorElement = document.getElementById(`${field}-error`);
            if (errorElement) {
                errorElement.textContent = errors[field][0]; 
            }
        }
    }

    // Xử lý sự kiện gửi Form
    productForm.addEventListener('submit', function(e) {
        e.preventDefault(); 

        const data = {
            name: document.getElementById('name').value,
            description: document.getElementById('description').value,
            price: document.getElementById('price').value,
            image_url: document.getElementById('image_url').value,
        };

        fetch(API_URL, {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json', 
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken // BẮT BUỘC cho POST
            },
            body: JSON.stringify(data)
        })
        .then(async response => {
            messageStatus.textContent = ''; 
            displayValidationErrors({}); 

            const result = await response.json();
            
            if (response.status === 422) {
                displayValidationErrors(result.errors);
                messageStatus.textContent = 'Lỗi validation. Vui lòng kiểm tra lại dữ liệu.';
                messageStatus.style.color = 'red';
                throw new Error('Validation Failed');
            }
            
            if (!response.ok) {
                 throw new Error(result.message || `HTTP error! Status: ${response.status}`);
            }

            return result;
        })
        .then(result => {
            // Thành công (201 Created)
            messageStatus.textContent = `Sản phẩm "${result.product.name}" đã được tạo thành công!`;
            messageStatus.style.color = 'green';
            
            productForm.reset(); 
            // 💡 Tùy chọn: Chuyển hướng về trang danh sách sau khi tạo
            // setTimeout(() => {
            //     window.location.href = '/products'; 
            // }, 1500); 
        })
        .catch(error => {
            console.error('Lỗi tạo sản phẩm:', error);
            if (error.message !== 'Validation Failed') {
                messageStatus.textContent = `Lỗi: ${error.message}`;
                messageStatus.style.color = 'red';
            }
        });
    });
});