<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bóng Quay Thưởng</title>
  <style>
    body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #444;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100vh;
    color: white;
    }

    .container {
    text-align: center;
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
    background: #999;
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
</body>
</html>
