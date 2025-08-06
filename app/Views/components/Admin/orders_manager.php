<!-- Quản lý Đơn hàng -->
<div id="orders" class="content-section">
    <div class="section-header">
        <h2>Quản lý Đơn hàng</h2>
        <button id="addOrderBtn" class="btn btn-primary">➕ Thêm Đơn Hàng</button>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Tìm kiếm đơn hàng..." />
    </div>

    <table class="table" id="ordersTable">
        <thead>
            <tr>
                <th>Mã Đơn</th> <!-- id_orders -->
                <th>Khách hàng</th> <!-- full_name (accounts) -->
                <th>PT Thanh toán</th> <!-- payment_method (invoices) -->
                <th>Tổng tiền</th> <!-- total_price (orders) -->
                <th>Điểm cộng</th> <!-- earned_points (invoices) -->
                <th>Ngày đặt</th> <!-- created_at (orders) -->
                <th>Trạng thái</th> <!-- status (orders) -->
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="ordersTableBody">
            <?php if (!empty($orders)): ?>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= $order['id_orders'] ?? '---' ?></td>
                <td><?= htmlspecialchars($order['full_name'] ?? '---') ?></td>
                <td><?= ucfirst($order['payment_method'] ?? '---') ?></td>
                <td><?= number_format($order['total_price'] ?? 0) ?>đ</td>
                <td><?= $order['earned_points'] ?? 0 ?></td>
                <td><?= isset($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '---' ?></td>
                <td><?= ucfirst($order['status'] ?? '---') ?></td>
                <td>
                    <button class="btn btn-sm btn-warning edit-order-btn"
                        data-order='<?= json_encode($order, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) ?>'>
                        Sửa
                    </button>

                    <button class="btn btn-sm btn-danger delete-order-btn"
                        data-id="<?= $order['id_orders'] ?>">Xoá</button>

                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="8">Không có đơn hàng nào.</td>
            </tr>
            <?php endif; ?>
        </tbody>

    </table>
</div>

<!-- Modal Sửa Đơn hàng -->
<div id="editOrderModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" id="closeEditOrderModal">&times;</span>
        <h3>Cập nhật đơn hàng</h3>

        <form id="editOrderForm" action="<?= BASE_URL ?>admin-update-order" method="POST">
            <!-- Đảm bảo có input hidden đúng name -->
            <input type="hidden"  name="orderId" id="editOrderId" value="<?= $order['id_orders'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Trạng thái đơn:</label>
                    <select name="orderStatus" id="editOrderStatus" class="form-control" required>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tổng tiền:</label>
                    <input type="number" name="totalPrice" id="editTotalPrice" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Số lượng:</label>
                    <input type="number" name="totalQuantity" id="editTotalQuantity" class="form-control" required />
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelEditOrderBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>


<!-- Modal Thêm Đơn hàng -->
<div id="orderModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeOrderModal">&times;</span>
        <h3 id="orderModalTitle">Thêm Đơn hàng</h3>
        <form id="orderForm" action="<?= BASE_URL ?>admin-create-order" method="POST">
            <input type="hidden" name="orderId" value="<?= $order['id_orders'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Khách hàng:</label>
                    <select id="accountId" name="accountId" class="form-control" required>
                        <option value="">-- Chọn khách hàng --</option>
                        <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id_accounts'] ?>"><?= $acc['full_name'] ?> (<?= $acc['email'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Trạng thái đơn hàng:</label>
                    <select id="orderStatus" name="orderStatus" class="form-control" required>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="delivered">Đã giao</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tổng tiền:</label>
                    <input type="number" id="totalPrice" name="totalPrice" class="form-control" step="0.01" required />
                </div>

                <div class="form-group">
                    <label>Số lượng sản phẩm:</label>
                    <input type="number" id="totalQuantity" name="totalQuantity" class="form-control" min="1"
                        required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phương thức thanh toán:</label>
                    <select id="paymentMethod" name="paymentMethod" class="form-control" required>
                        <option value="cash">Tiền mặt</option>
                        <option value="credit_card">Thẻ tín dụng</option>
                        <option value="momo">Momo</option>
                        <option value="zalo">ZaloPay</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Số tiền khách thanh toán:</label>
                    <input type="number" id="amountPaid" name="amountPaid" class="form-control" step="0.01" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Trạng thái thanh toán:</label>
                    <select id="isPaid" name="isPaid" class="form-control" required>
                        <option value="paid">Đã thanh toán</option>
                        <option value="unpaid">Chưa thanh toán</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Điểm tích lũy:</label>
                    <input type="number" id="earnedPoints" name="earnedPoints" class="form-control" min="0" />
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelOrderBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openOrderBtn = document.getElementById('addOrderBtn');
    const orderModal = document.getElementById('orderModal');
    const closeOrderBtn = document.getElementById('closeOrderModal');
    const cancelOrderBtn = document.getElementById('cancelOrderBtn');

    // Mở modal
    openOrderBtn.addEventListener('click', () => {
        orderModal.style.display = 'block';
    });

    // Đóng modal
    closeOrderBtn.addEventListener('click', () => {
        orderModal.style.display = 'none';
    });

    cancelOrderBtn.addEventListener('click', () => {
        orderModal.style.display = 'none';
    });

    // Click ngoài modal-content → đóng
    window.addEventListener('click', (e) => {
        if (e.target === orderModal) {
            orderModal.style.display = 'none';
        }
    });
});
</script>


<script>
// Bắt sự kiện click nút sửa
document.querySelectorAll('.edit-order-btn').forEach(button => {
    button.addEventListener('click', function() {
        try {
            const order = JSON.parse(this.dataset.order);

            // Set các giá trị vào form
            document.getElementById('editOrderId').value = order.id_orders ?? '';
            document.getElementById('editOrderStatus').value = order.status ?? 'pending';
            document.getElementById('editTotalPrice').value = order.total_price ?? 0;
            document.getElementById('editTotalQuantity').value = order.total_quantity ?? 1;

            document.getElementById('editOrderModal').style.display = 'block';
        } catch (err) {
            console.error("❌ Không thể parse dữ liệu đơn hàng:", err);
        }
    });
});

// Đóng modal
document.getElementById('cancelEditOrderBtn').onclick =
    document.getElementById('closeEditOrderModal').onclick = function() {
        document.getElementById('editOrderModal').style.display = 'none';
    };
</script>


<script>
document.querySelectorAll('.delete-order-btn').forEach(button => {
    button.addEventListener('click', function() {
        const orderId = this.dataset.id;
        if (confirm(`Bạn có chắc chắn muốn xoá đơn hàng #${orderId}?`)) {
            window.location.href = BASE_URL + `admin-delete-order?id=${orderId}`;
        }
    });
});
</script>