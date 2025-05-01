<?php
require_once '../includes/header.php';

$query = "
    SELECT u.name AS username, COUNT(l.id) AS leave_count 
    FROM leaves l 
    JOIN users u ON l.employee_id = u.id 
    GROUP BY l.employee_id
";

$result = mysqli_query($conn, $query);

$userLeaveData = [];
while ($row = mysqli_fetch_assoc($result)) {
    $userLeaveData[] = $row;
}


$userNames = array_column($userLeaveData, 'username');
$leaveCounts = array_column($userLeaveData, 'leave_count');
?>

<div class="row">
    <div class="col-12">
        <div class="page-title-box pb-3 d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Leaves Report</h4>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Leaves </h4>
                        <div id="bar-charts" style="height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var chartDom = document.getElementById('bar-charts');
        var myChart = echarts.init(chartDom);

        var userNames = <?php echo json_encode($userNames); ?>;
        var leaveCounts = <?php echo json_encode($leaveCounts); ?>;

        var option = {
            title: {
                text: '',
                left: 'center'
            },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'shadow'
                }
            },
            xAxis: {
                type: 'category',
                data: userNames,
                axisLabel: {
                    rotate: 0,
                    interval: 0
                }
            },
            yAxis: {
                type: 'value',
                name: 'Number of Leaves'
            },
            series: [{
                name: 'Leaves',
                type: 'bar',
                data: leaveCounts,
                barWidth: '10%',
                itemStyle: {
                    color: '#4e73df'
                },
                label: {
                    show: true,
                    position: 'top',
                    color: '#000',
                    fontWeight: 'bold'
                }
            }]
        };

        myChart.setOption(option);
    });
</script>

<?php require_once '../includes/footer.php'; ?>
