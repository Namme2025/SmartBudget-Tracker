<?php 
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
include 'db.php'; 

$uid = $_SESSION['user_id'];
$search = $_GET['search'] ?? '';
$selected_month = $_GET['month'] ?? date('m');
$selected_year = $_GET['year'] ?? date('Y');

// SQL Queries
$q = "SELECT * FROM transactions WHERE user_id = '$uid' AND MONTH(created_at) = '$selected_month' AND YEAR(created_at) = '$selected_year'";
if($search != '') { $q .= " AND title LIKE '%$search%'"; }
$res = mysqli_query($conn, $q . " ORDER BY id DESC");

$totals = mysqli_query($conn, "SELECT type, SUM(amount) as total FROM transactions WHERE user_id = '$uid' AND MONTH(created_at) = '$selected_month' AND YEAR(created_at) = '$selected_year' GROUP BY type");
$t_inc = 0; $t_exp = 0;
while($row = mysqli_fetch_assoc($totals)){
    if($row['type']=='income') $t_inc = $row['total']; else $t_exp = $row['total'];
}

$chart_q = mysqli_query($conn, "SELECT category, SUM(amount) as t FROM transactions WHERE user_id='$uid' AND type='expense' AND MONTH(created_at) = '$selected_month' AND YEAR(created_at) = '$selected_year' GROUP BY category");
$labels = []; $data = [];
while($c = mysqli_fetch_assoc($chart_q)){ $labels[] = $c['category']; $data[] = $c['t']; }

// Icon Mapping Function
function getIcon($category) {
    $icons = [
        'Food' => '🍔',
        'Bills' => '⚡',
        'Transport' => '🚗',
        'Salary' => '💰',
        'Education' => '📚',
        'Others' => '📦'
    ];
    return $icons[$category] ?? '🔹';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Budget Pro - Aesthetic Icons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { transition: 0.3s; }
        body.dark-mode { background-color: #121212 !important; color: #efefef !important; }
        .dark-mode .card { background-color: #1e1e1e !important; border: 1px solid #333 !important; color: #fff !important; }
        .dark-mode .table { color: #fff !important; }
        .dark-mode thead tr { background-color: #2a2a2a !important; color: #fff !important; border-bottom: 2px solid #444 !important; }
        .dark-mode td { border-color: #333 !important; }
        .dark-mode .form-control, .dark-mode .form-select { background-color: #252525 !important; color: #fff !important; border: 1px solid #444 !important; }
        .dark-mode .navbar { background-color: #000 !important; border-bottom: 1px solid #333; }
        .dark-mode .text-muted { color: #aaa !important; }
        .category-icon { font-size: 1.2rem; margin-right: 8px; }
    </style>
</head>
<body class="bg-light pb-5">

<nav class="navbar navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <span class="navbar-brand fw-bold">💸 SmartBudget</span>
        <div class="d-flex align-items-center">
            <div class="form-check form-switch me-3 text-white">
                <input class="form-check-input" type="checkbox" id="darkModeToggle" style="cursor: pointer;">
                <label class="form-check-label small ms-1" for="darkModeToggle">🌙 Dark Mode</label>
            </div>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="row h-100">
                <div class="col-md-6 mb-3">
                    <div class="card p-4 shadow-sm border-0 text-center">
                        <h6 class="text-uppercase small fw-bold">Income</h6>
                        <h2 class="text-success m-0">₱<?php echo number_format($t_inc, 2); ?></h2>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card p-4 shadow-sm border-0 text-center">
                        <h6 class="text-uppercase small fw-bold">Expense</h6>
                        <h2 class="text-danger m-0">₱<?php echo number_format($t_exp, 2); ?></h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card p-3 shadow-sm bg-dark text-white text-center border-0">
                        <h6 class="text-uppercase small fw-bold opacity-75">Net Balance</h6>
                        <h1 class="m-0">₱<?php echo number_format($t_inc - $t_exp, 2); ?></h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card p-3 shadow-sm border-0 h-100 text-center">
                <h6 class="fw-bold mb-3">Expense Chart</h6>
                <canvas id="budgetChart" style="max-height: 200px;"></canvas>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm mb-3">
                <h5 class="mb-3 fw-bold">Add Transaction</h5>
                <form action="add.php" method="POST">
                    <input type="text" name="t" class="form-control mb-2" placeholder="Item Name" required>
                    <input type="number" name="a" class="form-control mb-2" placeholder="Amount" step="0.01" required>
                    <select name="cat" id="categorySelect" class="form-select mb-2">
                        <option value="Food">🍔 Food</option>
                        <option value="Bills">⚡ Bills</option>
                        <option value="Transport">🚗 Transport</option>
                        <option value="Salary">💰 Salary</option>
                        <option value="Education">📚 Education</option>
                        <option value="Others">📦 Others</option>
                    </select>
                    <select name="ty" id="typeSelect" class="form-select mb-3">
                        <option value="expense">Expense (-)</option>
                        <option value="income">Income (+)</option>
                    </select>
                    <button name="save" class="btn btn-primary w-100 fw-bold">Add Entry</button>
                </form>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="m-0 fw-bold">Recent Activities</h5>
                    <form method="GET">
                        <select name="month" class="form-select form-select-sm" onchange="this.form.submit()">
                            <?php for($m=1; $m<=12; $m++): ?>
                                <option value="<?php echo sprintf('%02d', $m); ?>" <?php if($selected_month == $m) echo 'selected'; ?>>
                                    <?php echo date('F', mktime(0,0,0,$m,1)); ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr><th>Detail</th><th class="text-end">Amount</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                            <?php while($row = mysqli_fetch_assoc($res)): ?>
                            <tr>
                                <td>
                                    <span class="category-icon"><?php echo getIcon($row['category']); ?></span>
                                    <span class="fw-bold"><?php echo $row['title']; ?></span><br>
                                    <small class="text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                                </td>
                                <td class="text-end fw-bold <?php echo ($row['type']=='income')?'text-success':'text-danger'; ?>">
                                    <?php echo ($row['type']=='income')?'+':'-'; ?> ₱<?php echo number_format($row['amount'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-link text-danger p-0" onclick="return confirm('Delete?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// AUTO-SWITCH LOGIC: Salary -> Income
const categorySelect = document.getElementById('categorySelect');
const typeSelect = document.getElementById('typeSelect');

categorySelect.addEventListener('change', function() {
    if (this.value === 'Salary') {
        typeSelect.value = 'income';
    } else {
        typeSelect.value = 'expense';
    }
});

// DARK MODE LOGIC
const toggle = document.getElementById('darkModeToggle');
if (localStorage.getItem('dark-mode') === 'enabled') {
    document.body.classList.add('dark-mode');
    toggle.checked = true;
}
toggle.addEventListener('change', () => {
    if (toggle.checked) {
        document.body.classList.add('dark-mode');
        localStorage.setItem('dark-mode', 'enabled');
    } else {
        document.body.classList.remove('dark-mode');
        localStorage.setItem('dark-mode', 'disabled');
    }
});

// CHART LOGIC
new Chart(document.getElementById('budgetChart'), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            data: <?php echo json_encode($data); ?>,
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
            borderWidth: 0
        }]
    },
    options: { plugins: { legend: { position: 'bottom', labels: { color: (localStorage.getItem('dark-mode') === 'enabled' ? '#fff' : '#666') } } } }
});
</script>
</body>
</html>