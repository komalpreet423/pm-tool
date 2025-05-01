<?php
require_once '../includes/header.php';

$expensesData = [];
$sql = "SELECT DATE_FORMAT(expense_date, '%Y-%m') AS expense_month, SUM(amount) AS total_amount
        FROM expenses
        GROUP BY expense_month
        ORDER BY expense_month ASC";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $expensesData[] = [
        'date' => $row['expense_month'],
        'amount' => (float) $row['total_amount']
    ];
}

$expensesJson = json_encode($expensesData);
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Expense Report</h4>
        </div>
    </div>
</div>

<div class="col-md-12">
        <div class="row ">
            <div class="col-md-3 p-3">
                <label>Week</label>
                <select class="form-control" name="Week" id="Week">
                    <option value="" selected>All Week</option>
                    <option value="this-week">This Week</option>
                    <option value="previous-week">Previous Week</option>
                </select>
            </div>

            <div class="col-md-3 p-3">
                <label>Week</label>
                <select class="form-control" name="month" id="month">
                    <option value="" selected>All Month</option>
                    <option value="this-week">This Month</option>
                    <option value="previous-week">Previous Month</option>
                </select>
            </div>

            <div class="col-md-3">
                    <label for="start-date">Start Date</label>
                    <input type="text" class="form-control" id="date" name="date" >
            </div>

            <div class="col-md-3">
                <div class="mt-4">
                    <button type="button" id="reset-filters" class="btn btn-secondary">Reset Filters</button>
                </div>
            </div>

        </div>
    </div>

<div class="card ">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <h4 class="card-title "></h4>
                        <div id="line-chart" style="height: 400px;" data-colors='["--bs-success"]' class="e-charts"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    const expenseData = <?php echo $expensesJson; ?>;

    const dates = expenseData.map(item => item.date);
    const amounts = expenseData.map(item => item.amount);

    const chartDom = document.getElementById('line-chart');
    const myChart = echarts.init(chartDom);

    const option = {
        title: {
            text: 'Monthly Expenses'
        },
        tooltip: {
            trigger: 'axis'
        },
        xAxis: {
            type: 'category',
            data: dates
        },
        yAxis: {
            type: 'value',
            axisLabel: {
                formatter: '₹{value}'
            }
        },
        series: [{
            name: 'Total Expense',
            data: amounts,
            type: 'line',
            smooth: true,
            color: '#28a745'
        }]
    };

    myChart.setOption(option);
</script>

<?php require_once '../includes/footer.php'; ?>
