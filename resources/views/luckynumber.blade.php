<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách tham gia quay số trúng thưởng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
    <div class="container" style="width: 90%;">
        <!-- Title -->
        <h1 class="text-center mt-4">Danh sách tham gia quay số trúng thưởng</h1>
        <h2 class="text-center mb-4">Được cập nhật liên tục tới ngày: <span id="current-date"></span></h2>

        <!-- Search Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <input type="date" class="form-control" placeholder="Thời gian">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Họ tên">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Số điện thoại">
            </div>
            <div class="col-md-3">
                <input type="text" class="form-control" placeholder="Số may mắn">
            </div>
            <div class="col-md-12 mt-2 text-center">
                <button class="btn btn-primary">Tìm kiếm</button>
            </div>
        </div>

        <!-- Table -->
        <table class="table table-striped table-bordered" id="participant-table">
            <thead>
                <tr>
                    <th scope="col"><a href="#" class="text-decoration-none sort" data-sort="id">#</a></th>
                    <th scope="col"><a href="#" class="text-decoration-none sort" data-sort="name">Họ tên</a></th>
                    <th scope="col"><a href="#" class="text-decoration-none sort" data-sort="phone">Số điện thoại</a></th>
                    <th scope="col">Mã trúng thưởng</th>
                    <th scope="col">Ngày đăng ký</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>Nguyen Van A</td>
                    <td>0123456789</td>
                    <td>MT12345</td>
                    <td>15-01-2025</td>
                    <td><button class="btn btn-danger btn-sm">Xóa</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Le Thi B</td>
                    <td>0987654321</td>
                    <td>MT54321</td>
                    <td>14-01-2025</td>
                    <td><button class="btn btn-danger btn-sm">Xóa</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Tran Van C</td>
                    <td>0912345678</td>
                    <td>MT67890</td>
                    <td>13-01-2025</td>
                    <td><button class="btn btn-danger btn-sm">Xóa</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">&laquo; Trang đầu</a></li>
                <li class="page-item"><a class="page-link" href="#">50</a></li>
                <li class="page-item"><a class="page-link" href="#">49</a></li>
                <li class="page-item"><a class="page-link" href="#">48</a></li>
                <li class="page-item"><a class="page-link" href="#">&raquo; 10 trang tiếp theo</a></li>
            </ul>
        </nav>
    </div>

    <script>
        // Display the current date
        const currentDateElement = document.getElementById('current-date');
        const today = new Date();
        const formattedDate = today.toLocaleDateString('en-GB'); // Format as DD-MM-YYYY
        currentDateElement.textContent = formattedDate;

        // Sorting functionality
        $(document).on('click', '.sort', function(e) {
            e.preventDefault();
            const table = $('#participant-table tbody');
            const rows = table.find('tr').toArray();
            const sortKey = $(this).data('sort');

            rows.sort((a, b) => {
                const aVal = $(a).find(`td:nth-child(${sortKey === 'id' ? 1 : sortKey === 'name' ? 2 : 3})`).text().trim();
                const bVal = $(b).find(`td:nth-child(${sortKey === 'id' ? 1 : sortKey === 'name' ? 2 : 3})`).text().trim();

                if (sortKey === 'id') {
                    return parseInt(aVal) - parseInt(bVal);
                }

                return aVal.localeCompare(bVal);
            });

            table.html(rows);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
