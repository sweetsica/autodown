<!DOCTYPE html>
<html>

<head>
    <title>Down Flick Pictures</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(6, 1fr); /* 6 cột */
            gap: 10px; /* Khoảng cách giữa các ô */
        }

        .grid-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .grid-item img {
            max-width: 100%; /* Đảm bảo hình ảnh không vượt quá khung */
            height: auto;
            border-radius: 5px;
        }

        .grid-item strong {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #333;
        }
    </style>
</head>

<body>
    <a href="http://autodown.test:81/phone.php">Cyka Blyat</a>
    <form action="{{ route('showFlickr') }}" method="post">
        @csrf
        <input id="myInput" type="text" name="idimg" placeholder="Enter Flickr Image Link">
        <input id="myBtn" type="submit" name="btn-submit" value="Submit">
    </form>

    <h1>Photo Sizes</h1>
    @if(isset($photoSizes))
        <div class="grid-container">
            @foreach($photoSizes as $size)
                <div class="grid-item">
                    <strong>{{ $size['label'] }}</strong>
                    <a href="{{ $size['source'] }}" target="_blank">Download</a>
                    <img src="{{ $size['source'] }}" alt="{{ $size['label'] }}">
                </div>
            @endforeach
        </div>
    @else
        <p>No photo sizes available.</p>
    @endif

    <script>
        $("#myInput").keyup(function(event) {
            if (event.keyCode === 13) { // Nếu nhấn phím Enter
                $("#myBtn").click();
            }
        });
    </script>
</body>

</html>
