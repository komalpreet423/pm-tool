<?php
require_once '../includes/header.php';
$expensesData = [];
$conditions = [];
if (isset($_GET['daterange']) && $_GET['daterange'] !== '') {
    $daterange = $_GET['daterange'];
    list($startDate, $endDate) = explode(' - ', $daterange);
    $conditions[] = "expense_date BETWEEN '$startDate' AND '$endDate'";
} else {
    $startOfMonth = date('Y-m-01');
    $today = date('Y-m-d');
    $daterange = $startOfMonth . ' - ' . $today;
    $conditions[] = "expense_date BETWEEN '$startOfMonth' AND '$today'";
}
$whereClause = '';
if (!empty($conditions)) {
    $whereClause = ' WHERE ' . implode(' AND ', $conditions);
}
$sql = "SELECT DATE_FORMAT(expense_date, '%Y-%m-%d') AS expense_month, SUM(amount) AS total_amount 
        FROM expenses $whereClause 
        GROUP BY expense_month 
        ORDER BY expense_month ASC";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $expensesData[] = [
            'date' => $row['expense_month'],
            'amount' => (float) $row['total_amount']
        ];
    }
}
$hasData = !empty($expensesData);
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
    <div class="row">
        <div class="col-md-3 mb-3">
            <label>Date Range</label>
            <input type="text" class="form-control" id="daterange" name="daterange" value="<?php echo $daterange; ?>"
                autocomplete="off" />
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="line-chart" style="height: 400px;" class="e-charts"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>
<script>
    const hasData = <?php echo $hasData ? 'true' : 'false'; ?>;
</script>

<script>
    $(document).ready(function () {

        $('#daterange').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            },
            startDate: moment("<?php echo explode(' - ', $daterange)[0]; ?>"),
            endDate: moment("<?php echo explode(' - ', $daterange)[1]; ?>"),
            maxDate: moment()
        });


        $('#daterange').change(function () {
            const daterange = $('#daterange').val();
            let params = new URLSearchParams();
            if (daterange) params.append('daterange', daterange);
            window.location.href = '?' + params.toString();
        });


        const expenseData = <?php echo $expensesJson; ?>;
        const dates = expenseData.map(item => item.date);
        const amounts = expenseData.map(item => item.amount);

        const chartDom = document.getElementById('line-chart');
        const myChart = echarts.init(chartDom);

        if (!hasData) {
            myChart.clear();
            chartDom.innerHTML = '<div style="text-align:center;padding-top:150px;font-size:18px;color:#999;">No data found for selected date .</div>';
        } else {
            const option = {
                title: {
                    text: 'Daily Expenses'
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
        }
    });
</script>

<?php require_once '../includes/footer.php'; ?>