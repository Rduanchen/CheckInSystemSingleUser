<?php

// 设置时区
date_default_timezone_set('Asia/Taipei');

// 获取当前时间
$current_time = date('Y-m-d H:i:s');

// 定义CSV文件路径
$csv_file = 'record.csv';

// 检查请求类型
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];

    if ($action == 'CheckIn') {
        // 处理CheckIn
        $data = [$current_time, 'CheckIn'];
        $file = fopen($csv_file, 'a');
        fputcsv($file, $data);
        fclose($file);
        echo "CheckIn recorded at $current_time";
    } elseif ($action == 'CheckOut') {
        // 处理CheckOut
        $data = [$current_time, 'CheckOut'];
        $file = fopen($csv_file, 'a');
        fputcsv($file, $data);
        fclose($file);
        echo "CheckOut recorded at $current_time";
    } elseif ($action == 'Read') {
        // 读取CSV内容并生成HTML表格
        if (file_exists($csv_file)) {
            $file = fopen($csv_file, 'r');
            $csv_data = [];
            while (($row = fgetcsv($file)) !== false) {
                $csv_data[] = $row;
            }
            fclose($file);

            // 生成HTML表格
            echo '<table border="1">';
            echo '<tr><th>Time</th><th>Action</th></tr>';
            foreach ($csv_data as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

            // 提供CSV下载链接
            echo '<br><a href="./download.php">Download CSV</a>';
        } else {
            echo 'No records found.';
        }
    } elseif ($action == 'GetInfo') {
        if (file_exists($csv_file)) {
            $file = fopen($csv_file, 'r');
            $csv_data = [];
            while (($row = fgetcsv($file)) !== false) {
                $csv_data[] = $row;
            }
            fclose($file);
            // Return the CSV data as a JSON response
            header('Content-Type: application/json');
            echo json_encode($csv_data);
            exit;
        } else {
            echo 'No records to download.';
        }
    }
}
?>
