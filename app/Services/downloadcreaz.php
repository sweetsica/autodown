<?php
require_once 'simple_html_dom.php';

// Handle the form submission if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $response = [];

    try {
        if (isset($_POST['url']) && isset($_POST['folder'])) {
            $url = $_POST['url'];
            $folder = $_POST['folder'];
            $savePath = __DIR__ . "/downloads/" . $folder;

            if (!is_dir($savePath)) {
                mkdir($savePath, 0777, true);
            }

            $html = file_get_html($url);

            if (!$html) {
                $response['error'] = "Failed to retrieve URL: $url";
                throw new Exception($response['error']);
            }

            $images = [];
            foreach ($html->find('div.spectra-image-gallery__media.spectra-image-gallery__media--masonry') as $gallery) {
                foreach ($gallery->find('picture source') as $source) {
                    $imgSrc = $source->srcset;
                    $imgName = basename($imgSrc);
                    $imgPath = $savePath . '/' . $imgName;

                    try {
                        $imageContent = @file_get_contents($imgSrc);
                        if ($imageContent !== false) {
                            file_put_contents($imgPath, $imageContent);
                            $images[] = $imgName;
                        } else {
                            file_put_contents('error.txt', "Failed to find image: $imgSrc\n", FILE_APPEND);
                        }
                    } catch (Exception $e) {
                        file_put_contents('error.txt', "Failed to save image: $imgSrc\n", FILE_APPEND);
                    }
                }
            }

            if (empty($images)) {
                $response['error'] = 'No images found or downloaded.';
            } else {
                $response['images'] = $images;
            }
        } else {
            $response['error'] = 'URL or folder not provided.';
        }
    } catch (Exception $e) {
        $response['error'] = $e->getMessage();
    }

    // Log the response for debugging
    file_put_contents('response.log', json_encode($response) . "\n", FILE_APPEND);

    // Output JSON response
    echo json_encode($response);
    exit; // Exit after processing the AJAX request
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Scraper</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
    <style>
        #imageContainer {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 10px;
            margin-top: 20px;
        }
        #imageContainer img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Image Scraper</h1>
        <form id="scrapeForm" class="mt-4">
            <div class="form-group">
                <label for="url">Enter URL:</label>
                <input type="text" class="form-control" id="url" placeholder="https://example.com" required>
            </div>
            <div class="form-group">
                <label for="folder">Enter Folder Name:</label>
                <input type="text" class="form-control" id="folder" placeholder="Enter folder name" required>
            </div>
            <button type="submit" class="btn btn-primary">Scrape and Save</button>
        </form>

        <div class="mt-5" id="result">
            <h3>Downloaded Images:</h3>
            <div id="imageContainer"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
    <script>
        $('#scrapeForm').on('submit', function(e) {
            e.preventDefault();

            let url = $('#url').val();
            let folder = $('#folder').val();
            $('#imageContainer').empty(); // Clear previous images

            $.ajax({
                url: '',
                method: 'POST',
                data: { url: url, folder: folder },
                success: function(response) {
                    console.log('Response:', response);

                    try {
                        // Parse response as JSON if it's a string
                        let result = typeof response === 'string' ? JSON.parse(response) : response;

                        if (result.error) {
                            $('#imageContainer').append('<p class="text-danger">Error: ' + result.error + '</p>');
                        } else {
                            // Get the base URL of the current page including the folder path
                            let baseUrl = window.location.protocol + '//' + window.location.host + '/creaz';

                            result.images.forEach(function(image) {
                                let imgElement = $('<a>')
                                    .attr('href', baseUrl + '/downloads/' + folder + '/' + image)
                                    .attr('data-lightbox', 'gallery')
                                    .attr('data-title', image)
                                    .append($('<img>').attr('src', baseUrl + '/downloads/' + folder + '/' + image));
                                $('#imageContainer').append(imgElement);
                            });
                        }
                    } catch (e) {
                        $('#imageContainer').append('<p class="text-danger">Failed to parse response. Raw response: ' + response + '</p>');
                        console.error('Parsing Error:', e);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('AJAX Error:', status, error);
                    $('#imageContainer').append('<p class="text-danger">Failed to process the request. Status: ' + status + ', Error: ' + error + '</p>');
                }
            });
        });
    </script>
</body>
</html>
