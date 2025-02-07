<!-- edited -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BeautyGreen - Bóng Quay Thưởng</title>
  <link type="text/css" rel="stylesheet" href="{{asset('assets/css/luckynumber.css')}}">
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
  <a href="{{route('quay-thuong-v2')}}"><img class="corner-image top-left" src="{{asset('assets/images/left-1.png')}}" alt="Top Left"></a>
  <a href="{{route('quaythuong-v2')}}"><img class="corner-image top-right" src="{{asset('assets/images/right-1.png')}}" alt="Top Right"></a>
  <!-- <img class="corner-image bottom-left" src="http://example.com/image3.png" alt="Bottom Left">
  <img class="corner-image bottom-right" src="http://example.com/image4.png" alt="Bottom Right"> -->

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
  const excludedNumbers = ["0142", "0427", "1293", "0768", "0031","148","423","1291","769","30"]; 

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
        do {
            currentResult = Math.floor(Math.random() * 1500) + 1;
            currentResult = currentResult.toString().padStart(4, '0'); // Chuyển số thành chuỗi có 4 chữ số
        } while (excludedNumbers.includes(currentResult)); // Nếu số bị loại bỏ thì quay tiếp

        bigBall.textContent = currentResult;
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
        smallBalls[index].textContent = result;
    });
  }
</script>

<a href="{{route('quaythuong')}}"><p style='text-align:center'>Lucky Number - Quay số trúng thưởng <br>Made by S @ 2025</p></a>
</body>
</html>
