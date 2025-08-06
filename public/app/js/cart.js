


document.querySelectorAll(".add-to-cart").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();

    const id = parseInt(this.dataset.id);
    const name = this.dataset.name;
    const price = parseFloat(this.dataset.price);
    const image = this.dataset.image;
    const quantity = parseInt(this.dataset.quantity);

    const product = { id, name, price, image, quantity };

    addToCart(product);
    fetchCartAndRender();
  });
});

function fetchCartAndRender() {
  fetch(`${BASE_URL}api/cart.php?action=get`)
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") renderCart(data.data);
    });
}

function renderCart(cart) {
  const list = document.querySelector(".mini-cart-list");
  const empty = document.querySelector(".mini-cart-empty");
  const total = document.querySelector(".mini-cart-total");

  list.innerHTML = "";
  if (!cart || cart.length === 0) {
    empty.style.display = "block";
    total.textContent = "0đ";
    return;
  }

  empty.style.display = "none";
  let totalPrice = 0;

  cart.forEach((item) => {
    totalPrice += item.price * item.quantity;

    const itemDiv = document.createElement("div");
    itemDiv.style.cssText =
      "display:flex; align-items:center; padding:10px; border:1px solid #eee; border-radius:8px; margin-bottom:10px;";
    itemDiv.innerHTML = `
      <img src="${item.image_url}" alt="${
      item.name
    }" style="width:50px; height:50px; border-radius:5px; object-fit:cover; margin-right:10px;">
      <div style="flex:1;">
        <div style="font-weight:600; margin-bottom:5px;">${item.name}</div>
        <div style="color:#d0021b; font-weight:bold; margin-bottom:5px;">
  ${Number(item.price).toLocaleString("vi-VN")}đ
</div>
</div>
<div style="display:flex; align-items:center; gap:5px;">
  <button onclick="changeQuantity(${
    item.id_products
  }, -1)" style="width:25px; height:25px; border:none; background:#f0f0f0; border-radius:50%; cursor:pointer;">-</button>
  <span style="width:30px; text-align:center; font-weight:bold; color:#000;">${
    item.quantity
  }</span>
  <button onclick="changeQuantity(${
    item.id_products
  }, 1)" style="width:25px; height:25px; border:none; background:#f0f0f0; border-radius:50%; cursor:pointer;">+</button>
  <button onclick="removeItem(${
    item.id_products
  })" style="margin-left:10px; background:none; border:none; color:red; cursor:pointer;">🗑️</button>
</div>
</div>
`;
    list.appendChild(itemDiv);
  });

  total.textContent = totalPrice.toLocaleString() + "đ";
}

function addToCart(product) {
  fetch(`${BASE_URL}api/cart.php?action=add`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(product),
  }).then(() => {
    fetchCartAndRender();
    showCart();
  });
}

function changeQuantity(id, change) {
  fetch(`${BASE_URL}api/cart.php?action=change_quantity`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, change }), // ✅ thêm id vào payload
  }).then(() => fetchCartAndRender());
}

function removeItem(id) {
  fetch(`${BASE_URL}api/cart.php?action=remove`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id }),
  }).then(() => fetchCartAndRender());
}

function showCart() {
  document.querySelector(".mini-cart-overlay").style.display = "block";
  document.querySelector(".mini-cart-popup").style.display = "block";
}

function hideCart() {
  document.querySelector(".mini-cart-overlay").style.display = "none";
  document.querySelector(".mini-cart-popup").style.display = "none";
}

document.addEventListener("DOMContentLoaded", function () {
  fetchCartAndRender();

  const openBtn = document.querySelector(".cart");
  if (openBtn) {
    openBtn.addEventListener("click", (e) => {
      e.preventDefault();
      showCart();
    });
  }

  document
    .querySelector(".close-mini-cart")
    ?.addEventListener("click", hideCart);
  document
    .querySelector(".mini-cart-overlay")
    ?.addEventListener("click", hideCart);
});
