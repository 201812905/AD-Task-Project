let editingProduct = false;
let editingUser = false;
let currentEditId = null;

function showMessage(containerId, message, type = 'success') {
    const container = document.getElementById(containerId);
    container.innerHTML = `<div class="${type}-message">${message}</div>`;
    setTimeout(() => {
        container.innerHTML = '';
    }, 5000);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleString();
}

async function makeAjaxRequest(action, data = {}) {
    const formData = new FormData();
    formData.append('action', action);
    
    for (const [key, value] of Object.entries(data)) {
        formData.append(key, value);
    }

    try {
        const response = await fetch('', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        return result;
    } catch (error) {
        console.error('AJAX Error:', error);
        return { success: false, error: 'Network error occurred' };
    }
}

document.querySelectorAll('.admin-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.admin-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.admin-section').forEach(s => s.classList.remove('active'));
        
        tab.classList.add('active');
        document.getElementById(tab.dataset.section).classList.add('active');
        
        const section = tab.dataset.section;
        if (section === 'products') {
            loadProducts();
        } else if (section === 'users') {
            loadUsers();
        } else if (section === 'orders') {
            loadOrders();
        }
    });
});

async function loadStats() {
    try {
        const result = await makeAjaxRequest('get_stats');
        if (result.success) {
            document.getElementById('statProducts').textContent = result.data.products;
            document.getElementById('statUsers').textContent = result.data.users;
            document.getElementById('statOrders').textContent = result.data.orders;
            document.getElementById('statLowStock').textContent = result.data.low_stock;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadProducts() {
    const loading = document.getElementById('productsLoading');
    const table = document.getElementById('productsTable');
    
    loading.style.display = 'block';
    table.style.display = 'none';

    try {
        const result = await makeAjaxRequest('get_products');
        
        if (result.success) {
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            
            result.data.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${formatCurrency(product.price)}</td>
                    <td>${product.stock_quantity}</td>
                    <td>${product.category || 'N/A'}</td>
                    <td><span class="status-badge status-${product.status}">${product.status}</span></td>
                    <td>
                        <button class="admin-btn" onclick="editProduct('${product.id}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="admin-btn danger" onclick="deleteProduct('${product.id}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            table.style.display = 'table';
        } else {
            showMessage('productMessages', 'Error loading products: ' + result.error, 'error');
        }
    } catch (error) {
        showMessage('productMessages', 'Error loading products: ' + error.message, 'error');
    } finally {
        loading.style.display = 'none';
    }
}

async function editProduct(id) {
    try {
        const result = await makeAjaxRequest('get_products');
        if (result.success) {
            const product = result.data.find(p => p.id === id);
            if (product) {
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productPrice').value = product.price;
                document.getElementById('productStock').value = product.stock_quantity;
                document.getElementById('productCategory').value = product.category || '';
                document.getElementById('productImage').value = product.image_url || '';
                document.getElementById('productStatus').value = product.status;
                document.getElementById('productDescription').value = product.description || '';
                
                editingProduct = true;
                currentEditId = id;
                document.querySelector('#productForm button').innerHTML = '<i class="fas fa-save"></i> Update Product';
            }
        }
    } catch (error) {
        showMessage('productMessages', 'Error loading product: ' + error.message, 'error');
    }
}

async function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        try {
            const result = await makeAjaxRequest('delete_product', { id });
            if (result.success) {
                showMessage('productMessages', 'Product deleted successfully!');
                loadProducts();
                loadStats();
            } else {
                showMessage('productMessages', 'Error deleting product: ' + result.error, 'error');
            }
        } catch (error) {
            showMessage('productMessages', 'Error deleting product: ' + error.message, 'error');
        }
    }
}

async function loadUsers() {
    const loading = document.getElementById('usersLoading');
    const table = document.getElementById('usersTable');
    
    loading.style.display = 'block';
    table.style.display = 'none';

    try {
        const result = await makeAjaxRequest('get_users');
        
        if (result.success) {
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            
            result.data.forEach(user => {
                const row = document.createElement('tr');
                const fullName = `${user.first_name} ${user.middle_name || ''} ${user.last_name}`.trim();
                
                row.innerHTML = `
                    <td>${user.username}</td>
                    <td>${fullName}</td>
                    <td>${user.email || 'N/A'}</td>
                    <td><span class="role-badge role-${user.role}">${user.role}</span></td>
                    <td>${formatDate(user.created_at)}</td>
                    <td>
                        <button class="admin-btn" onclick="editUser('${user.id}')">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        ${user.role === 'banned' ? 
                            `<button class="admin-btn success" onclick="unbanUser('${user.id}')">
                                <i class="fas fa-user-check"></i> Unban
                            </button>` :
                            `<button class="admin-btn danger" onclick="banUser('${user.id}')">
                                <i class="fas fa-user-slash"></i> Ban
                            </button>`
                        }
                        <button class="admin-btn danger" onclick="deleteUser('${user.id}')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
            
            table.style.display = 'table';
        } else {
            showMessage('userMessages', 'Error loading users: ' + result.error, 'error');
        }
    } catch (error) {
        showMessage('userMessages', 'Error loading users: ' + error.message, 'error');
    } finally {
        loading.style.display = 'none';
    }
}

async function editUser(id) {
    try {
        const result = await makeAjaxRequest('get_users');
        if (result.success) {
            const user = result.data.find(u => u.id === id);
            if (user) {
                document.getElementById('userId').value = user.id;
                document.getElementById('userUsername').value = user.username;
                document.getElementById('userEmail').value = user.email || '';
                document.getElementById('userFirstName').value = user.first_name;
                document.getElementById('userMiddleName').value = user.middle_name || '';
                document.getElementById('userLastName').value = user.last_name;
                document.getElementById('userRole').value = user.role;
                document.getElementById('userPassword').value = '';
                document.getElementById('userPassword').placeholder = 'Leave blank to keep current password';
                
                editingUser = true;
                currentEditId = id;
                document.querySelector('#userForm button').innerHTML = '<i class="fas fa-save"></i> Update User';
            }
        }
    } catch (error) {
        showMessage('userMessages', 'Error loading user: ' + error.message, 'error');
    }
}

async function deleteUser(id) {
    if (confirm('Are you sure you want to delete this user?')) {
        try {
            const result = await makeAjaxRequest('delete_user', { id });
            if (result.success) {
                showMessage('userMessages', 'User deleted successfully!');
                loadUsers();
                loadStats();
            } else {
                showMessage('userMessages', 'Error deleting user: ' + result.error, 'error');
            }
        } catch (error) {
            showMessage('userMessages', 'Error deleting user: ' + error.message, 'error');
        }
    }
}

async function banUser(id) {
    if (confirm('Are you sure you want to ban this user?')) {
        try {
            const result = await makeAjaxRequest('ban_user', { id });
            if (result.success) {
                showMessage('userMessages', 'User banned successfully!');
                loadUsers();
                loadStats();
            } else {
                showMessage('userMessages', 'Error banning user: ' + result.error, 'error');
            }
        } catch (error) {
            showMessage('userMessages', 'Error banning user: ' + error.message, 'error');
        }
    }
}

async function unbanUser(id) {
    if (confirm('Are you sure you want to unban this user?')) {
        try {
            const result = await makeAjaxRequest('unban_user', { id });
            if (result.success) {
                showMessage('userMessages', 'User unbanned successfully!');
                loadUsers();
                loadStats();
            } else {
                showMessage('userMessages', 'Error unbanning user: ' + result.error, 'error');
            }
        } catch (error) {
            showMessage('userMessages', 'Error unbanning user: ' + error.message, 'error');
        }
    }
}

async function loadOrders() {
    const loading = document.getElementById('ordersLoading');
    const table = document.getElementById('ordersTable');
    
    loading.style.display = 'block';
    table.style.display = 'none';

    try {
        const result = await makeAjaxRequest('get_orders');
        
        if (result.success) {
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            
            if (result.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No orders found</td></tr>';
            } else {
                result.data.forEach(order => {
                    const row = document.createElement('tr');
                    const customerName = order.full_name || `${order.first_name || ''} ${order.last_name || ''}`.trim() || 'Unknown';
                    
                    row.innerHTML = `
                        <td>#${order.id}</td>
                        <td>${customerName}</td>
                        <td>${order.email}</td>
                        <td>${formatCurrency(order.total)}</td>
                        <td><span class="status-badge status-${order.status || 'pending'}">${order.status || 'pending'}</span></td>
                        <td>${formatDate(order.order_date)}</td>
                        <td>
                            <button class="admin-btn" onclick="viewOrder('${order.id}')">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
            
            table.style.display = 'table';
        } else {
            showMessage('orderMessages', 'Error loading orders: ' + result.error, 'error');
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        const tbody = table.querySelector('tbody');
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center;">No orders available</td></tr>';
        table.style.display = 'table';
    } finally {
        loading.style.display = 'none';
    }
}

function viewOrder(id) {
    alert('Order details functionality coming soon for order #' + id);
}

document.getElementById('productForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = editingProduct ? 'update_product' : 'add_product';
    
    const data = {};
    for (const [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    try {
        const result = await makeAjaxRequest(action, data);
        
        if (result.success) {
            showMessage('productMessages', editingProduct ? 'Product updated successfully!' : 'Product added successfully!');
            this.reset();
            editingProduct = false;
            currentEditId = null;
            document.querySelector('#productForm button').innerHTML = '<i class="fas fa-plus"></i> Add Product';
            loadProducts();
            loadStats();
        } else {
            showMessage('productMessages', 'Error: ' + result.error, 'error');
        }
    } catch (error) {
        showMessage('productMessages', 'Error: ' + error.message, 'error');
    }
});

document.getElementById('userForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const action = editingUser ? 'update_user' : 'add_user';
    
    const data = {};
    for (const [key, value] of formData.entries()) {
        data[key] = value;
    }
    
    try {
        const result = await makeAjaxRequest(action, data);
        
        if (result.success) {
            showMessage('userMessages', editingUser ? 'User updated successfully!' : 'User added successfully!');
            this.reset();
            editingUser = false;
            currentEditId = null;
            document.querySelector('#userForm button').innerHTML = '<i class="fas fa-plus"></i> Add User';
            document.getElementById('userPassword').placeholder = 'Password';
            loadUsers();
            loadStats();
        } else {
            showMessage('userMessages', 'Error: ' + result.error, 'error');
        }
    } catch (error) {
        showMessage('userMessages', 'Error: ' + error.message, 'error');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadProducts();
});
