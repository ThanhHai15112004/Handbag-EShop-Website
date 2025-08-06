<!-- Quản lý Giao hàng -->
<div id="delivery" class="content-section">
    <div class="section-header">
        <h2>Quản lý Giao hàng</h2>
        <button id="addDeliveryBtn" class="btn btn-primary">➕ Thêm Giao hàng</button>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Tìm kiếm giao hàng..." onkeyup="searchTable('deliveryTable', this.value)" />
    </div>

    <table class="table" id="deliveryTable">
        <thead>
            <tr>
                <th>Mã GH</th>
                <th>Đơn hàng</th>
                <th>Người giao (Shipper)</th>
                <th>Địa chỉ giao hàng</th>
                <th>Trạng thái</th>
                <th>Ngày gửi</th>
                <th>Ngày giao</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="deliveryTableBody">
            <?php if (empty($deliveries)): ?>
                <tr>
                    <td colspan="8" class="text-center text-muted">
                        Không có đơn giao hàng nào.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($deliveries as $delivery): ?>
                <tr>
                    <td>#<?= $delivery['id_delivery'] ?></td>
                    <td>ĐH #<?= $delivery['id_orders'] ?></td>
                    <td><?= htmlspecialchars($delivery['full_name']) ?></td>
                    <td><?= htmlspecialchars($delivery['shipping_address']) ?></td>
                    <td><?= ucfirst($delivery['delivery_status']) ?></td>
                    <td><?= $delivery['shipped_at'] ? date('d/m/Y H:i', strtotime($delivery['shipped_at'])) : 'Chưa giao' ?></td>
                    <td><?= $delivery['delivered_at'] ? date('d/m/Y H:i', strtotime($delivery['delivered_at'])) : 'Chưa nhận' ?></td>
                    <td>
                        <button class="btn btn-warning edit-delivery-btn"
                                data-delivery='<?= json_encode($delivery) ?>'>
                            Sửa
                        </button>
                        <button class="btn btn-sm btn-danger delete-delivery-btn" 
                                data-id="<?= $delivery['id_delivery'] ?>">
                            Xoá
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<!-- Modal Sửa Giao hàng -->
<div id="editDeliveryModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditDeliveryModal">&times;</span>
        <h3>Cập nhật Giao hàng</h3>
        <form id="editDeliveryForm" action="<?= BASE_URL ?>admin-update-delivery" method="POST">
            <input type="hidden" name="id_delivery" id="editDeliveryId" />

            <div class="form-row">
                <div class="form-group">
                    <label>Đơn hàng:</label>
                    <select name="id_orders" id="editOrderId" class="form-control" required>
                        <option value="">Chọn đơn hàng</option>
                        <?php foreach ($orders as $order): ?>
                            <option value="<?= $order['id_orders'] ?>">
                                <?= isset($order['order_code']) 
                                    ? htmlspecialchars($order['order_code']) 
                                    : 'ĐH #' . $order['id_orders'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Nhân viên giao:</label>
                    <select name="id_accounts" id="editAccountId" class="form-control" required>
                        <option value="">Chọn tài khoản</option>
                        <?php foreach ($shippers as $shipper): ?>
                            <option value="<?= $shipper['id_accounts'] ?>">
                                <?= htmlspecialchars($shipper['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Địa chỉ giao hàng:</label>
                <input type="text" name="shipping_address" id="editShippingAddress" class="form-control" required />
            </div>

            <div class="form-group">
                <label>Trạng thái giao hàng:</label>
                <select name="delivery_status" id="editDeliveryStatus" class="form-control" required>
                    <option value="pending">Chờ giao</option>
                    <option value="shipping">Đang giao</option>
                    <option value="delivered">Đã giao</option>
                    <option value="failed">Thất bại</option>
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ngày giao:</label>
                    <input type="datetime-local" name="shipped_at" id="editShippedAt" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Ngày nhận:</label>
                    <input type="datetime-local" name="delivered_at" id="editDeliveredAt" class="form-control" />
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelEditDeliveryBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Cập nhật</button>
            </div>
        </form>

    </div>
</div>



<!-- Modal Giao hàng -->
<div id="deliveryModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeDeliveryModal">&times;</span>
        <h3 id="deliveryModalTitle">Thêm Giao hàng</h3>
        <form id="deliveryForm" method="POST" action="<?= BASE_URL ?>admin-create-delivery">
            <input type="hidden" id="deliveryId" />
            <div class="form-row">
                <div class="form-group">
                    <label>Đơn hàng:</label>
                    <select name="id_orders" id="orderId" class="form-control" required>
                        <option value="">Chọn đơn hàng</option>
                            <?php foreach ($orders as $order): ?>
                                <option value="<?= $order['id_orders'] ?>">
                                    <?= isset($order['order_code']) 
                                        ? htmlspecialchars($order['order_code']) 
                                        : 'ĐH #' . $order['id_orders'] ?>
                                </option>
                            <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Người giao (Tài khoản):</label>
                    <select name="id_accounts" id="accountId" class="form-control" required>
                        <option value="">Chọn người giao</option>
                        <?php foreach ($shippers as $shipper): ?>
                            <option value="<?= $shipper['id_accounts'] ?>">
                                <?= $shipper['full_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Địa chỉ giao hàng:</label>
                    <input type="text" name="shipping_address" id="shippingAddress" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Trạng thái giao hàng:</label>
                    <select name="delivery_status" id="deliveryStatus" class="form-control" required>
                        <option value="pending">Chờ xử lý</option>
                        <option value="shipping">Đang giao</option>
                        <option value="delivered">Đã giao</option>
                        <option value="failed">Thất bại</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ngày giao hàng:</label>
                    <input type="datetime-local" name="shipped_at" id="shippedAt" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Ngày hoàn tất:</label>
                    <input type="datetime-local" name="delivered_at" id="deliveredAt" class="form-control" />
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelDeliveryBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const openDeliveryBtn = document.getElementById('addDeliveryBtn');
    const deliveryModal = document.getElementById('deliveryModal');
    const closeDeliveryBtn = document.getElementById('closeDeliveryModal');
    const cancelDeliveryBtn = document.getElementById('cancelDeliveryBtn');

    openDeliveryBtn.addEventListener('click', () => {
        document.getElementById('deliveryForm').reset(); // Reset form khi mở
        deliveryModal.style.display = 'block';
    });

    closeDeliveryBtn.addEventListener('click', () => {
        deliveryModal.style.display = 'none';
    });

    cancelDeliveryBtn.addEventListener('click', () => {
        deliveryModal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === deliveryModal) {
            deliveryModal.style.display = 'none';
        }
    });
});
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editDeliveryModal');

    document.querySelectorAll('.edit-delivery-btn').forEach(button => {
        button.addEventListener('click', function () {
            const delivery = JSON.parse(this.dataset.delivery);

            document.getElementById('editDeliveryId').value = delivery.id_delivery;
            document.getElementById('editOrderId').value = delivery.id_orders;
            document.getElementById('editAccountId').value = delivery.id_accounts;
            document.getElementById('editShippingAddress').value = delivery.shipping_address;
            document.getElementById('editDeliveryStatus').value = delivery.delivery_status;
            document.getElementById('editShippedAt').value = delivery.shipped_at || '';
            document.getElementById('editDeliveredAt').value = delivery.delivered_at || '';

            editModal.style.display = 'block';
        });
    });

    document.getElementById('closeEditDeliveryModal').addEventListener('click', () => {
        editModal.style.display = 'none';
    });

    window.addEventListener('click', function (e) {
        if (e.target === editModal) {
            editModal.style.display = 'none';
        }
    });
});

</script>



<script>
document.querySelectorAll('.delete-delivery-btn').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        if (confirm("Bạn chắc chắn muốn xóa đơn giao hàng này?")) {
            window.location.href = BASE_URL + `admin-delete-delivery?id=${id}`;
        }
    });
});
</script>
