<?php

// Định nghĩa không gian tên (namespace) cho Controller này.
// Đây là vị trí chuẩn cho các Controller API trong cấu trúc mặc định của Laravel.
namespace App\Http\Controllers\Api;

// Import (sử dụng) lớp Controller cơ sở của Laravel.
use App\Http\Controllers\Controller;
// Import lớp Request để xử lý dữ liệu từ yêu cầu HTTP.
use Illuminate\Http\Request;
// Import Model Product, đại diện cho bảng 'products' trong cơ sở dữ liệu.
use App\Models\Product;
// Import Facade Validator để thực hiện việc kiểm tra dữ liệu đầu vào.
use Illuminate\Support\Facades\Validator;

/**
 * Lớp ProductController:
 * Quản lý các thao tác CRUD (Create, Read, Update, Delete) cho tài nguyên Product
 * thông qua các API endpoint.
 *
 * Kế thừa từ lớp Controller cơ sở.
 */
class ProductController extends Controller
{
    /**
     * Phương thức index: Lấy danh sách tất cả sản phẩm.
     * Tương ứng với request: GET /api/products
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Lấy tất cả sản phẩm từ cơ sở dữ liệu, sắp xếp theo thời gian tạo mới nhất.
        $products = Product::latest()->get();

        // Trả về danh sách sản phẩm dưới dạng JSON với mã trạng thái HTTP mặc định (200 OK).
        return response()->json($products);
    }

    /**
     * Phương thức store: Tạo một sản phẩm mới.
     * Tương ứng với request: POST /api/products
     *
     * @param  \Illuminate\Http\Request  $request Đối tượng chứa dữ liệu yêu cầu từ client.
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Khởi tạo đối tượng Validator để kiểm tra dữ liệu đầu vào ($request->all()).
        $validator = Validator::make($request->all(), [
            // 'name' là bắt buộc (required), phải là duy nhất trong bảng 'products' ở cột 'name'.
            'name' => 'required|unique:products,name',
            // 'description' là tùy chọn (nullable) và phải là chuỗi (string).
            'description' => 'nullable|string',
            // 'price' là bắt buộc, phải là số (numeric) và không được nhỏ hơn 0 (min:0).
            'price' => 'required|numeric|min:0',
            // 'image_url' là tùy chọn và phải là một URL hợp lệ (url).
            'image_url' => 'nullable|url',
        ]);

        // Kiểm tra xem việc xác thực (validation) có thất bại hay không.
        if ($validator->fails()) {
            // Nếu thất bại, trả về lỗi validation dưới dạng JSON với mã trạng thái 422 Unprocessable Entity.
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Tạo sản phẩm mới trong cơ sở dữ liệu, chỉ sử dụng dữ liệu đã được xác thực hợp lệ.
        $product = Product::create($validator->validated());

        // Trả về thông báo thành công, dữ liệu sản phẩm vừa tạo và mã trạng thái 201 Created.
        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product,
        ], 201);
    }

    /**
     * Phương thức show: Lấy thông tin chi tiết của một sản phẩm theo ID.
     * Tương ứng với request: GET /api/products/{id}
     *
     * @param  int  $id ID của sản phẩm cần tìm.
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Tìm sản phẩm theo ID. Nếu không tìm thấy, trả về null.
        $product = Product::find($id);

        // Kiểm tra nếu không tìm thấy sản phẩm.
        if (!$product) {
            // Trả về thông báo lỗi "Product not found" với mã trạng thái 404 Not Found.
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Trả về thông tin chi tiết của sản phẩm dưới dạng JSON (200 OK).
        return response()->json($product);
    }

    /**
     * Phương thức update: Cập nhật thông tin của một sản phẩm theo ID.
     * Tương ứng với request: PUT/PATCH /api/products/{id}
     *
     * @param  \Illuminate\Http\Request  $request Đối tượng chứa dữ liệu yêu cầu từ client.
     * @param  int  $id ID của sản phẩm cần cập nhật.
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        // Tìm sản phẩm theo ID.
        $product = Product::find($id);

        // Kiểm tra nếu không tìm thấy sản phẩm.
        if (!$product) {
            // Trả về thông báo lỗi "Product not found" với mã trạng thái 404 Not Found.
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Khởi tạo đối tượng Validator để kiểm tra dữ liệu đầu vào.
        $validator = Validator::make($request->all(), [
            // 'name' là bắt buộc, phải là duy nhất. Cú pháp unique:products,name,' . $id
            // cho phép bỏ qua chính ID của sản phẩm đang được cập nhật,
            // nghĩa là sản phẩm có thể giữ nguyên tên hiện tại của nó.
            'name' => 'required|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_url' => 'nullable|url',
        ]);

        // Kiểm tra xem việc xác thực (validation) có thất bại hay không.
        if ($validator->fails()) {
            // Nếu thất bại, trả về lỗi validation dưới dạng JSON với mã trạng thái 422.
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Cập nhật sản phẩm trong cơ sở dữ liệu với dữ liệu đã được xác thực.
        $product->update($validator->validated());

        // Trả về thông báo thành công và dữ liệu sản phẩm đã được cập nhật (200 OK).
        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product,
        ]);
    }

    /**
     * Phương thức destroy: Xóa một sản phẩm theo ID.
     * Tương ứng với request: DELETE /api/products/{id}
     *
     * @param  int  $id ID của sản phẩm cần xóa.
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Tìm sản phẩm theo ID.
        $product = Product::find($id);

        // Kiểm tra nếu không tìm thấy sản phẩm.
        if (!$product) {
            // Trả về thông báo lỗi "Product not found" với mã trạng thái 404 Not Found.
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Xóa sản phẩm khỏi cơ sở dữ liệu.
        $product->delete();

        // Trả về thông báo thành công (200 OK).
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
