<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply For Gate Pass or Leave</title>
<style>
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

body, html {
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #f0f2f5;
}

.container {
    width: 100%;
    max-width: 900px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

.header {
    background-color: #317dbf;
    padding: 15px;
    color: white;
    text-align: center;
    border-radius: 5px 5px 0 0;
}

.form-container {
    padding: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.form-group label {
    font-size: 16px;
    margin-bottom: 5px;
}

.form-group select, 
.form-group input[type="time"],input[type="date"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
}

.form-group select:focus, 
.form-group input[type="time"]:focus {
    outline: none;
    border-color: #317dbf;
}

.required {
    color: red;
}

.submit-btn {
    padding: 10px 20px;
    background-color: #317dbf;
    color: white;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
}

.submit-btn:hover {
    background-color: #255f8a;
}

</style>
</head>
<body>

    <div class="container">
        <!-- Header -->
        <header class="header">
            <h1>Apply For Gate Pass</h1>
        </header>

        <!-- Form Section -->
        <div class="form-container" >
        <form id="gatePassForm" method="post" action="insert_data.php">
    <div class="form-group">
        <label for="selectType">Select Type <span class="required">*</span></label>
        <select id="selectType" name="type" required>  <!-- Changed name to 'type' -->
            <option value="" disabled selected>Select type</option>
            <option value="gatepass">Gate Pass</option>
            <option value="leave">Leave</option>
        </select>
    </div>

    <div class="form-group">
        <label for="reason">Reason <span class="required">*</span></label>
        <select id="reason" name="reason" required>
            <option value="" disabled selected>Select</option>
            <option value="college">College</option>
            <option value="home">Go to Home</option>
            <option value="salon">Salon</option>
            <option value="other">Other</option>
        </select>
    </div>

    <div class="form-group">
        <label for="outDate">Out Date *</label>
        <input type="date" id="outdate" name="outdate" required>
    </div>

    <div class="form-group">
        <label for="ReturnDate">Return Date *</label>
        <input type="date" id="returndate" name="returndate" required>  
    </div>

    <div class="form-group">
        <label for="outTime">Approx Out Time <span class="required">*</span></label>
        <input type="time" id="outtime" name="outtime" required>  
    </div>

    <div class="form-group">
        <label for="inTime">Approx In Time <span class="required">*</span></label>
        <input type="time" id="intime" name="intime" required>
    </div>

    <button type="submit" class="submit-btn">Submit</button>
</form>

        </div>
    </div>

<script>
    document.getElementById('gatePassForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const selectType = document.getElementById('selectType').value;
    const reason = document.getElementById('reason').value;
    const outTime = document.getElementById('outtime').value;
    const inTime = document.getElementById('intime').value;
    const outDate = document.getElementById('outdate').value;
    const ReturnDate = document.getElementById('returndate').value;

    if (selectType && reason && outTime && inTime && outDate && ReturnDate) {
        alert(Successfully submitted:\n\nType: ${selectType}\nReason: ${reason}\nOut Time: ${outTime}\nIn Time: ${inTime}\noutDate: ${outDate}\nReturnDate: ${ReturnDate});
    } else {
        alert('Please fill in all required fields.');
    }
});

</script>
</body>
</html>