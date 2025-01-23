<!-- edited -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BeautyGreen - Bóng Quay Thưởng</title>
  <style>
    html, body {
      margin: 0;
      padding: 0;
      height: 100%; /* Đảm bảo rằng cả html và body đều có chiều cao 100% */
    }
    a {
      text-decoration: none; /* Loại bỏ gạch chân */
      color: white; /* Thay đổi màu chữ thành màu xám đậm (bạn có thể tùy chỉnh màu này) */
    }
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      color: white;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background-color: #444; /* Màu nền mặc định */
      background-image: url('{{asset('assets/images/29a80869-9ab4-457d-b473-b6c881cef393.jfif')}}'); Thay đường dẫn ảnh tại đây
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed; /* Giữ nền cố định khi cuộn trang */
    }

    /* Dùng màu nền khi màn hình quá lớn */
    @media (min-width: 2560px) {
      body {
        background-image: none;
        background-color: #333; /* Thay đổi màu nền lớn hơn 2K */
      }
    }

    .container {
      text-align: center;
      position: relative;
      padding: 20px;
      border-radius: 8px;
      background: rgba(4, 87, 40, 1); /* Nền xanh lục nhạt với độ mờ 5% */
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .big-ball {
      width: 200px;
      height: 200px;
      border-radius: 50%;
      background: #ff5050;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 48px;
      font-weight: bold;
      margin: 20px auto;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .small-balls {
      display: flex;
      justify-content: space-between;
      width: 500px;
      margin: 20px auto;
    }

    .small-ball {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: #dfc822;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: bold;
    }

    button {
      margin: 5px;
      padding: 10px 20px;
      border: none;
      background: #007bff;
      color: white;
      font-size: 16px;
      cursor: pointer;
      border-radius: 4px;
    }

    button:disabled {
      background: #999;
      cursor: not-allowed;
    }

    /* 4 ảnh cố định ở 4 góc màn hình */
    .corner-image {
      position: fixed;
      width: auto !important; /* Kích thước tự động theo ảnh gốc */
      height: auto !important; /* Giữ nguyên chiều cao thực tế của ảnh */
      max-width: none !important; /* Không giới hạn chiều rộng */
      max-height: none !important; /* Không giới hạn chiều cao */
      display: block; /* Làm ảnh hiển thị thành khối */
    }

    /* Vị trí từng góc */
    .top-left {
      top: 10px;
      left: 10px;
    }

    .top-right {
      top: 10px;
      right: 10px;
    }

    .bottom-left {
      bottom: 10px;
      left: 10px;
    }

    .bottom-right {
      bottom: 10px;
      right: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="big-ball">0000</div>
    <div class="small-balls">
      <div class="small-ball"></div>
      <div class="small-ball"></div>
      <div class="small-ball"></div>
      <div class="small-ball"></div>
      <div class="small-ball"></div>
    </div>
    <button class="start-button">Bắt đầu</button>
    <button class="stop-button" disabled>Dừng lại</button>
    <button class="reset-button">Tạo mới</button>
  </div>

  <!-- 4 ảnh cố định -->
  <img class="corner-image top-left" src="{{asset('assets/images/left-1.png')}}" alt="Top Left">
  <img class="corner-image top-right" src="{{asset('assets/images/right-1.png')}}" alt="Top Right">

<script>
  document.querySelectorAll('.corner-image').forEach(img => {
    img.onload = function() {
      console.log(`Kích thước gốc của ảnh: ${img.naturalWidth}x${img.naturalHeight}`);
      console.log(`Kích thước hiển thị: ${img.width}x${img.height}`);
    }
  });
</script>
<script>
    let interval;
    let currentResult = 0;
    let spinning = false;
    const results = [];

    const bigBall = document.querySelector('.big-ball');
    const smallBalls = document.querySelectorAll('.small-ball');
    const startButton = document.querySelector('.start-button');
    const stopButton = document.querySelector('.stop-button');
    const resetButton = document.querySelector('.reset-button');

    startButton.addEventListener('click', () => {
    if (!spinning) {
        startButton.textContent = "Tiếp tục";
        spinning = true;
    }
    startButton.disabled = true;
    stopButton.disabled = false;

    let speed = 50;
    interval = setInterval(() => {
        currentResult = Math.floor(Math.random() * 1500) + 1;
        bigBall.textContent = currentResult.toString().padStart(4, '0');
    }, speed);
    });

    stopButton.addEventListener('click', () => {
    stopButton.disabled = true;
    clearInterval(interval);

    if (!results.includes(currentResult)) {
        results.push(currentResult);
        updateSmallBalls();
    }

    if (results.length < 5) {
        spinning = false;
        startButton.disabled = false;
    } else {
        startButton.disabled = true;
    }
    });

    resetButton.addEventListener('click', () => {
    results.splice(0, results.length);
    currentResult = 0;
    spinning = false;
    bigBall.textContent = '0000';
    startButton.textContent = "Bắt đầu";
    smallBalls.forEach(ball => ball.textContent = '');
    startButton.disabled = false;
    stopButton.disabled = true;
    });

    function updateSmallBalls() {
    results.forEach((result, index) => {
        smallBalls[index].textContent = result.toString().padStart(4, '0');
    });
    }

  </script>

<p style='text-align:center'>Lucky Number - Quay số trúng thưởng <br>Made by S @ 2025</p>
</body>
</html>
