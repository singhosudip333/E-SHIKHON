<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course - Step 1</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
/* General Page Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
}

/* Form Container */
.container {
    margin-top: 50px;
    margin-bottom: 50px;
}

/* Card Styling */
.card {
    border: 1px solid #dee2e6;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    border-radius: 10px;
}

/* Form Labels */
.form-label {
    font-weight: bold;
    color: #495057;
}

/* Button Styling */
button {
    font-size: 18px;
    font-weight: bold;
}

/* Input Focus */
input:focus, select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 5px rgba(13, 110, 253, 0.5);
}

/* Validation Feedback */
.invalid-feedback {
    font-size: 14px;
    color: red;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Add Course - Step 1</h1>
        <div class="card mx-auto p-4" style="max-width: 600px;">
            <form id="basicCourseForm" action="step2.php" method="POST">
                <!-- Course Title -->
                <div class="mb-3">
                    <label for="title" class="form-label">Course Title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter course title" required>
                    <div class="invalid-feedback">Course title is required.</div>
                </div>
                <!-- Course Category -->
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="Programming">Programming</option>
                        <option value="Design">Design</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Business">Business</option>
                        <option value="Art">Art</option>
                    </select>
                    <div class="invalid-feedback">Please select a category.</div>
                </div>
                <!-- Continue Button -->
                <button type="submit" class="btn btn-primary w-100">Continue</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('basicCourseForm');

    form.addEventListener('submit', function (event) {
        let isValid = true;

        
        const title = document.getElementById('title');
        if (!title.value.trim()) {
            title.classList.add('is-invalid');
            isValid = false;
        } else {
            title.classList.remove('is-invalid');
        }

       
        const category = document.getElementById('category');
        if (!category.value) {
            category.classList.add('is-invalid');
            isValid = false;
        } else {
            category.classList.remove('is-invalid');
        }

    
        if (!isValid) {
            event.preventDefault();
        }
    });
});

    </script>
</body>
</html>
