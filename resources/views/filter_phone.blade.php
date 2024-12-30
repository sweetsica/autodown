<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phone Filter</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Lọc Số Điện Thoại</h1>

        <!-- Form nhập nội dung -->
        <form method="GET" action="" class="mb-4">
            <div class="mb-3">
                <label for="content" class="form-label">Nhập nội dung:</label>
                <textarea name="content" id="content" class="form-control" rows="8" placeholder="Nhập nội dung tại đây...">{{ $_POST['content'] ?? '' }}</textarea>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Lọc số</button>
        </form>

        <?php
        if (isset($_GET['submit'])) {
            $content = $_GET['content'];

            // Quy tắc regex để lọc số điện thoại
            $rule = '/\+?\(?([0-9]{3})\)?[-.]?\(?([0-9]{3})\)?[-.]?\(?([0-9]{4})\)?/';
            $lines = explode("\n", $content);
            $results = preg_grep($rule, $lines);

            // Hiển thị kết quả
            if (!empty($results)) {
                echo '<div class="mb-3">';
                echo '<label for="result" class="form-label">Số điện thoại lọc được:</label>';
                echo '<textarea id="result" class="form-control" rows="8" readonly>';
                echo implode("\n", $results);
                echo '</textarea>';
                echo '</div>';
            } else {
                echo '<p class="text-danger">Không tìm thấy số điện thoại hợp lệ.</p>';
            }
        }
        ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
