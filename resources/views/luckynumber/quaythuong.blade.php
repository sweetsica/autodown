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
  <a href="{{route('quaythuong')}}"><img class="corner-image top-left" src="{{asset('assets/images/left-1.png')}}" alt="Top Left"></a>
  <a href="{{route('quaythuong')}}"><img class="corner-image top-right" src="{{asset('assets/images/right-1.png')}}" alt="Top Right"></a>

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
<a href="{{route('quaythuong')}}"><p style='text-align:center'>An khang</p></a> - </a><a href="{{route('quaythuong')}}"><p style='text-align:center'>Thịnh vượng</p></a> - </a><a href="{{route('quaythuong')}}"><p style='text-align:center'>Vạn sự như ý</p></a>
<p style='text-align:center'>Lucky Number - Quay số trúng thưởng <br>Made by S @ 2025</p>
</body>
</html>
