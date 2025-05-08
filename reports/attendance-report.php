<?php
require_once '../includes/header.php';
$user_values = userProfile();

if($user_values['role'] && ($user_values['role'] !== 'hr' && $user_values['role'] !== 'admin'))
{
    $redirectUrl = $_SERVER['HTTP_REFERER'] ?? '/pm-tool';
    $_SESSION['toast'] = "Access denied. Employees only.";
    header("Location: " . $redirectUrl); 
    exit();
}


function mapStatus($status)
{
    switch ($status) {
        case 'present':
            return 'Present';
        case 'late':
            return 'Late';
        case 'short_leave':
            return 'Short Leave';
        case 'half_day':
            return 'Half Day';
        case 'absent':
            return 'Absent';
        default:
            return 'Unknown';
    }
}


$week_start = date('Y-m-d', strtotime('monday this week'));
$week_end = date('Y-m-d', strtotime('sunday this week'));
$week_sql = "SELECT status, COUNT(*) as count FROM attendance WHERE date BETWEEN '$week_start' AND '$week_end' GROUP BY status";
$week_result = mysqli_query($conn, $week_sql);
$weekly_data = [];
while ($row = mysqli_fetch_assoc($week_result)) {
    $row['status'] = mapStatus($row['status']);
    $weekly_data[] = $row;
}


$month_start = date('Y-m-01');
$month_end = date('Y-m-t');
$month_sql = "SELECT status, COUNT(*) as count FROM attendance WHERE date BETWEEN '$month_start' AND '$month_end' GROUP BY status";
$month_result = mysqli_query($conn, $month_sql);
$monthly_data = [];
while ($row = mysqli_fetch_assoc($month_result)) {
    $row['status'] = mapStatus($row['status']);
    $monthly_data[] = $row;
}


$prev_month_start = date('Y-m-01', strtotime('first day of last month'));
$prev_month_end = date('Y-m-t', strtotime('last day of last month'));
$prev_month_sql = "SELECT status, COUNT(*) as count FROM attendance WHERE date BETWEEN '$prev_month_start' AND '$prev_month_end' GROUP BY status";
$prev_month_result = mysqli_query($conn, $prev_month_sql);
$previous_month_data = [];
while ($row = mysqli_fetch_assoc($prev_month_result)) {
    $row['status'] = mapStatus($row['status']);
    $previous_month_data[] = $row;
}
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Attendance Report</h4>
        </div>
    </div>
</div>
<div class="card">
<div class="row" >
<div class="col-12">
        <div class=""></div>
    </div>
</div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                       
                        <div id="weekly_chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                       
                        <div id="monthly_chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        
                        <div id="previous_month_chart" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>

    const weeklyData = <?php echo json_encode($weekly_data); ?>;
    const monthlyData = <?php echo json_encode($monthly_data); ?>;
    const previousMonthData = <?php echo json_encode($previous_month_data); ?>;


    const formatData = (data) => data.map(item => ({
        name: item.status,
        value: parseInt(item.count)
    }));


    const weeklyChart = echarts.init(document.getElementById('weekly_chart'));
    weeklyChart.setOption({
        title: { text: 'Weekly Attendance', left: 'center' },
        tooltip: { trigger: 'item' },
        legend: { bottom: '0%', left: 'center' },
        series: [{
            name: 'Status',
            type: 'pie',
            radius: '50%',
            data: formatData(weeklyData),
            emphasis: {
                itemStyle: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }]
    });


    const monthlyChart = echarts.init(document.getElementById('monthly_chart'));
    monthlyChart.setOption({
        title: { text: 'Monthly Attendance', left: 'center' },
        tooltip: { trigger: 'item' },
        legend: { bottom: '0%', left: 'center' },
        series: [{
            name: 'Status',
            type: 'pie',
            radius: '50%',
            data: formatData(monthlyData)
        }]
    });


    const previousMonthChart = echarts.init(document.getElementById('previous_month_chart'));
    previousMonthChart.setOption({
        title: { text: 'Previous Month Attendance', left: 'center' },
        tooltip: { trigger: 'item' },
        legend: { bottom: '0%', left: 'center' },
        series: [{
            name: 'Status',
            type: 'pie',
            radius: '50%',
            data: formatData(previousMonthData)
        }]
    });
</script>

<?php require_once '../includes/footer.php'; ?>