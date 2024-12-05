<?php
include("../config/connect.php");
if (isset($_REQUEST['submitPhanTich'])) {

    $stardate = $_REQUEST['txtstartdate'];
    $enddate = $_REQUEST['txtenddate'];

    if ($stardate > $enddate) {
        echo '<script>alert("Ngày bắt đầu phải nhỏ hơn ngày kết thúc");</script>';
    }
    $tongtienphong = 0;
    $sql_tk = "SELECT * FROM phongdat WHERE ngay_dat_phong BETWEEN '$stardate' AND '$enddate'";
    $result = mysqli_query($conn, $sql_tk);
    $row = mysqli_num_rows($result);
    if (mysqli_num_rows($result) > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['ngay_dat_phong'];
            $sotien[] = $row['tong_tien'];
            $tongtienphong += $row['tong_tien'];
        }
    } else {
        echo '<h3 class="font-weight-light text-center">Không có dữ liệu</h3>';
    }
    $tongtien = number_format($tongtienphong);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/common.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-title {
            margin-top: 20px;
        }
        .info-container {
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .form-container {
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .summary-container {
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .responseMessage {
            margin-top: 10px;
        }
        .responseMessage__success {
            color: green;
        }
        .responseMessage__error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php require('inc/admin-navbar1.php') ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Thống Kê Doanh Thu</h1>
                </div>
                <div class="info-container">
                    <div class="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
                    <script>
                        // setup 
                        const data = {
                            labels: <?php echo json_encode($data); ?>,
                            datasets: [{
                                label: 'Tiền',
                                data: <?php echo json_encode($sotien); ?>,
                                backgroundColor: 'rgba(255, 26, 104, 0.2)',
                                borderColor: 'rgba(255, 26, 104, 1)',
                                borderWidth: 1
                            }]
                        };

                        // config 
                        const config = {
                            type: 'bar',
                            data,
                            options: {
                                scales: {
                                    x: {
                                        type: 'time',
                                        time: {
                                            unit: 'day',
                                        }
                                    },
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        };

                        const myChart = new Chart(
                            document.getElementById('myChart'),
                            config
                        );

                        const chartVersion = document.getElementById('chartVersion');
                        chartVersion.innerText = Chart.version;
                    </script>
                </div>
                <div class="summary-container">
                    <h5>Tổng Doanh Thu: <?php if(isset($tongtien)){echo $tongtien;}else {echo "0";}?> VNĐ</h5>
                </div>
                <div class="form-container">
                    <form method="post">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="startdate">Thời gian bắt đầu</label>
                                <input type="date" class="form-control" id="startdate" name="txtstartdate" value="2024-01-01">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="enddate">Thời gian kết thúc</label>
                                <input type="date" class="form-control" id="enddate" name="txtenddate" value="2024-11-30">
                            </div>
                        </div>
                        <button name="submitPhanTich" class="btn btn-primary d-flex justify-content-center mx-auto">Phân tích</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
</body>
</html>
