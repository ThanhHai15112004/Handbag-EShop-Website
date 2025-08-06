<!-- Quản lý Tài khoản -->
<div id="accounts" class="content-section">
    <div class="section-header">
        <h2>Quản lý Tài khoản</h2>
        <button id="addAccountBtn" class="btn btn-primary">➕ Thêm Tài khoản</button>
    </div>

    <div class="search-box">
        <input type="text" placeholder="Tìm kiếm tài khoản..." />
    </div>

    <table class="table table-bordered table-hover" id="accountsTable">
        <thead class="table-dark">
            <tr>
                <th>Ảnh</th>
                <th>Mã KH</th>
                <th>Email</th>
                <th>Họ tên</th>
                <th>Số ĐT</th>
                <th>Quyền</th>
                <th>Điểm</th>
                <th>Cấp độ</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody id="accountsTableBody">
            <?php if (!empty($accounts)): ?>
            <?php foreach ($accounts as $acc): ?>
            <tr>
                <td>
                    <img 
                        src="<?= BASE_URL . ($acc['avatar_url'] ?? 'assets/images/avatar/default-avatar.png') ?>" 
                        width="40" class="rounded-circle" />
                </td>

                <td>
                    <?= $acc['id_accounts'] ?>
                </td>
                <td>
                    <?= $acc['email'] ?>
                </td>
                <td>
                    <?= $acc['full_name'] ?>
                </td>
                <td>
                    <?= $acc['phone'] ?>
                </td>
                <td>
                    <span class="badge-custom bg-info"><?= $acc['role'] ?? 'Không rõ' ?></span>

                </td>
                <td>
                    <?= $acc['point_balance'] ?? 0 ?>
                </td>
                <td>
                    <?= $acc['level_name'] ?? 'Chưa có' ?>
                </td>
                <td>
                    <span class="badge <?= $acc['is_active'] ? 'bg-success text-white' : 'bg-danger text-white' ?>">
                        <?= $acc['is_active'] ? 'Hoạt động' : 'Chưa kích hoạt' ?>
                    </span>

                </td>
                <td>
                    <?= date('d/m/Y', strtotime($acc['created_at'])) ?>
                </td>

                <td>
                    <button class="btn btn-sm btn-warning">Sửa</button>
                    <button class="btn btn-sm btn-danger delete-account-btn"
                            data-id="<?= $acc['id_accounts'] ?>"
                            data-name="<?= htmlspecialchars($acc['full_name']) ?>">
                        Xoá
                    </button>

                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="11" class="text-center text-muted">Không có tài khoản nào</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>


<style>
.badge-custom {
    padding: 5px 10px;
    border-radius: 6px;
    color: black;
}
.badge-active {
    background-color: #28a745;
}
.badge-inactive {
    background-color: #dc3545;
}

</style>



<!-- Modal Sửa Tài khoản -->
<div id="editAccountModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeEditAccountModal">&times;</span>
        <h3>Chỉnh sửa Tài khoản</h3>

        <form id="editAccountForm" action="<?= BASE_URL ?>admin-update-account" method="POST">
            <input type="hidden" id="editAccountId" name="accountId" />

            <div class="form-row">
                <div class="form-group">
                    <label>Ảnh đại diện (URL):</label>
                    <input type="text" id="editAvatarUrl" name="avatarUrl" class="form-control" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="editEmail" name="email" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Họ tên:</label>
                    <input type="text" id="editFullName" name="fullName" class="form-control" required />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input type="tel" id="editPhone" name="phone" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Mật khẩu:</label>
                    <input type="password" id="editPassword" name="password" class="form-control" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quyền:</label>
                    <select id="editRole" name="role" class="form-control" required>
                        <option value="user">Khách hàng</option>
                        <option value="staff">Nhân viên</option>
                        <option value="shipper">Shipper</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Điểm:</label>
                    <input type="number" id="editPoints" name="points" class="form-control" min="0" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Cấp độ:</label>
                    <select id="editMembershipLevel" name="membershipLevel" class="form-control">
                        <option value="">-- Chọn cấp độ --</option>
                        <?php foreach ($membershipLevels as $level): ?>
                            <option value="<?= $level['id_level'] ?>"><?= $level['level_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Trạng thái:</label>
                    <select id="editAccountStatus" name="accountStatus" class="form-control">
                        <option value="1">Hoạt động</option>
                        <option value="0">Chưa kích hoạt</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-secondary" id="cancelEditAccountBtn">Hủy</button>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </div>
        </form>
    </div>
</div>



<!-- Modal Tài khoản -->
<div id="accountModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeAccountModal">&times;</span>
        <h3 id="accountModalTitle">Thêm / Chỉnh sửa Tài khoản</h3>

        <form id="accountForm" action="<?= BASE_URL ?>admin-create-account" method="POST">
            <!-- Hidden ID -->
            <input type="hidden" id="accountId" name="accountId" />
            <div class="form-row">
                <!-- Ảnh đại diện -->
                <div class="form-group">
                    <label>Ảnh đại diện (URL):</label>
                    <input type="text" id="avatarUrl" name="avatarUrl" class="form-control"
                        placeholder="/assets/images/avatar/image1.png" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Họ tên:</label>
                    <input type="text" id="fullName" name="fullName" class="form-control" required />

                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Số điện thoại:</label>
                    <input type="tel" id="phone" name="phone" class="form-control" required />
                </div>
                <div class="form-group">
                    <label>Mật khẩu:</label>
                    <input type="password" id="password" name="password" class="form-control" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Quyền:</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="user">Khách hàng</option>
                        <option value="staff">Nhân viên</option>
                        <option value="shipper">Shipper</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tích điểm:</label>
                    <input type="number" id="points" name="points" class="form-control" value="0" min="0" />
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Cấp độ (Level):</label>
                    <select id="membershipLevel" name="membershipLevel" class="form-control">
                        <option value="">-- Chọn cấp độ --</option>
                        <?php foreach ($membershipLevels as $level): ?>
                            <option value="<?= $level['id_level'] ?>">
                                <?= htmlspecialchars($level['level_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Trạng thái:</label>
                    <select id="accountStatus" name="accountStatus" class="form-control">
                        <option value="1">Hoạt động</option>
                        <option value="0">Chưa kích hoạt</option>
                    </select>
                </div>
            </div>

            <div style="text-align: right; margin-top: 20px">
                <button type="button" class="btn btn-danger" id="cancelAccountBtn">Hủy</button>
                <button type="submit" class="btn btn-success">Lưu</button>
            </div>
        </form>
    </div>
</div>




<script>
    window.addEventListener("DOMContentLoaded", () => {
        const flash = document.querySelector(".flash-message");
        if (flash) {
            setTimeout(() => flash.style.display = "none", 5000);
        }
    });
</script>


<!-- open/close modal -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const addBtn = document.getElementById('addAccountBtn');
    const modal = document.getElementById('accountModal');
    const closeBtn = document.getElementById('closeAccountModal');
    const cancelBtn = document.getElementById('cancelAccountBtn');

    addBtn.addEventListener('click', function() {
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    cancelBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
});
</script>




<script>
    document.querySelectorAll('.btn-warning').forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');

            document.getElementById('editAccountId').value = row.children[1].textContent.trim();
            document.getElementById('editEmail').value = row.children[2].textContent.trim();
            document.getElementById('editFullName').value = row.children[3].textContent.trim();
            document.getElementById('editPhone').value = row.children[4].textContent.trim();
            document.getElementById('editRole').value = row.children[5].innerText.trim();
            document.getElementById('editPoints').value = row.children[6].innerText.trim();
            document.getElementById('editAvatarUrl').value = row.querySelector('img').getAttribute('src');

            const level = row.children[7].textContent.trim();
            const levelSelect = document.getElementById('editMembershipLevel');
            for (let option of levelSelect.options) {
                option.selected = option.textContent.trim() === level;
            }

            const active = row.children[8].textContent.includes('Hoạt động') ? '1' : '0';
            document.getElementById('editAccountStatus').value = active;

            // reset mật khẩu
            document.getElementById('editPassword').value = '';

            // show modal
            document.getElementById('editAccountModal').style.display = 'block';
        });
    });

    document.getElementById('closeEditAccountModal').addEventListener('click', function () {
        document.getElementById('editAccountModal').style.display = 'none';
    });

    document.getElementById('cancelEditAccountBtn').addEventListener('click', function () {
        document.getElementById('editAccountModal').style.display = 'none';
    });

    window.addEventListener('click', function (e) {
        const modal = document.getElementById('editAccountModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });


</script>



<script>
document.querySelectorAll('.delete-account-btn').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.getAttribute('data-id');
        const name = this.getAttribute('data-name');

        if (confirm(`⚠️ Bạn có chắc chắn muốn xoá tài khoản: "${name}"?`)) {
            window.location.href = BASE_URL + `admin-delete-account?id=${id}`;
        }
    });
});
</script>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editAccountForm');

    form.addEventListener('submit', function (e) {
        const email = document.getElementById('editEmail').value.trim();
        const fullName = document.getElementById('editFullName').value.trim();
        const role = document.getElementById('editRole').value;
        const points = document.getElementById('editPoints').value.trim();
        const membershipLevel = document.getElementById('editMembershipLevel').value;
        const accountStatus = document.getElementById('editAccountStatus').value;

        let errors = [];

        if (email === '' || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            errors.push("❌ Email không hợp lệ.");
        }

        if (fullName.length < 2) {
            errors.push("❌ Họ tên cần ít nhất 2 ký tự.");
        }

        if (!['user', 'staff', 'shipper', 'admin'].includes(role)) {
            errors.push("❌ Quyền không hợp lệ.");
        }

        if (points === '' || isNaN(points) || parseInt(points) < 0) {
            errors.push("❌ Điểm phải là số >= 0.");
        }

        if (membershipLevel === '') {
            errors.push("❌ Vui lòng chọn cấp độ thành viên.");
        }

        if (!['1', '0'].includes(accountStatus)) {
            errors.push("❌ Trạng thái tài khoản không hợp lệ.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join("\n"));
        }
    });
});
</script>