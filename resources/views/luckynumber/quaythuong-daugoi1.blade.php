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
  <a href="{{route('quaythuong-giainhat')}}"><img class="corner-image top-left" src="{{asset('assets/images/left-1.png')}}" alt="Top Left"></a>
  <a href="{{route('quaythuong-binhthuong')}}"><img class="corner-image top-right" src="{{asset('assets/images/right-1.png')}}" alt="Top Right"></a>
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
    const predefined = [148,423]; // Hai giá trị cố định
    const finalOrder = Array.from({ length: 5 }, (_, i) => i); // Mảng từ 0 đến 4 để xáo trộn
    let currentStep = 0;

    shuffleArray(finalOrder); // Xáo trộn thứ tự xuất hiện của các giá trị

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

    interval = setInterval(() => {
        currentResult = Math.floor(Math.random() * 1500) + 1; // Quay số ngẫu nhiên
        bigBall.textContent = currentResult.toString().padStart(4, '0'); // Hiển thị số
    }, 50);
    });

    stopButton.addEventListener('click', () => {
    stopButton.disabled = true;

    // Giảm tốc độ trước khi dừng hẳn
    let delay = 50;
    const slowDownInterval = setInterval(() => {
        currentResult = Math.floor(Math.random() * 1500) + 1;
        bigBall.textContent = currentResult.toString().padStart(4, '0');
        delay += 50; // Tăng khoảng thời gian để tạo hiệu ứng chậm dần

        if (delay >= 800) {
        clearInterval(slowDownInterval);
        finalizeResult(); // Hiển thị giá trị cố định hoặc ngẫu nhiên
        }
    }, delay);
    });

    resetButton.addEventListener('click', () => {
        results.splice(0, results.length);
        currentResult = 0;
        spinning = false;
        currentStep = 0;
        shuffleArray(finalOrder); // Xáo trộn lại thứ tự khi làm mới
        bigBall.textContent = '0000';
        startButton.textContent = "Bắt đầu";
        smallBalls.forEach(ball => ball.textContent = '');
        startButton.disabled = false;
        stopButton.disabled = true;
    });

    function finalizeResult() {
        clearInterval(interval);

        const nextIndex = finalOrder[currentStep]; // Lấy vị trí tiếp theo trong thứ tự xáo trộn
        currentStep++;

        // Xác định kết quả là số cố định nếu thuộc hai vị trí đầu của `predefined`
        if (nextIndex < predefined.length) {
            currentResult = predefined[nextIndex];
        } else {
            // Giá trị ngẫu nhiên cho các vị trí còn lại
            do {
            currentResult = Math.floor(Math.random() * 1500) + 1;
            } while (predefined.includes(currentResult)); // Tránh trùng với số cố định
        }

        bigBall.textContent = currentResult.toString().padStart(4, '0'); // Hiển thị kết quả
        results.push(currentResult);

        updateSmallBalls();

        if (results.length < 5) {
            spinning = false;
            startButton.disabled = false;
        } else {
            startButton.disabled = true;
        }
    }

    function updateSmallBalls() {
        results.forEach((result, index) => {
            smallBalls[index].textContent = result.toString().padStart(4, '0');
        });
    }

    // Hàm xáo trộn mảng
    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }
  </script>
  <a href="{{route('quaythuong-daugoi1')}}" style="display:inline-block;">
    <p style="text-align:center; margin: 0;">An khang</p>
  </a> - 
  <a href="{{route('quaythuong-daugoi2')}}" style="display:inline-block;">
      <p style="text-align:center; margin: 0;">Thịnh vượng</p>
  </a> - 
  <a href="{{route('quaythuong-daugoi3')}}" style="display:inline-block;">
      <p style="text-align:center; margin: 0;">Vạn sự như ý</p>
  </a>
  <a href="#"><p style='text-align:center'>Lucky Number - Quay số trúng thưởng <br>Made by S @ 2025</p></a>
</body>
</html>
