<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Main Menu - WebsiteAI</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <style>
    .image-preview {
        width: 100%;
        height: auto;
        max-height: 200px;
        margin-top: 10px;
        border: 1px solid #ddd;
        padding: 5px;
    }
</style>

</head>
<body>
    <div class="d-flex justify-content-center">
        <h1>This is Mainpage</h1>
    </div>

    <div class="row">
        <div class="col-3">

            <div class="m-2">
        
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        
            @if (session('response'))
                <div class="alert alert-info">
                    <pre>{{ json_encode(session('response'), JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
            
            <form action="{{ route('backend.storebreed') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="image">Choose an image:</label>
                    <input type="file" name="image" class="form-control" id="image" accept="image/*" onchange="previewImage(event)">
                </div>
                <div class="form-group">
                    <img id="image-preview" class="image-preview" src="https://via.placeholder.com/200" alt="Image Preview">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary" name="action" value="breed">breed detect</button>
                    <button type="submit" class="btn btn-primary" name="action" value="weight">Weight detect</button>
                </div>
            </form>
        
            </div>

        </div>
    </div>

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('image-preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>

</body>
</html>