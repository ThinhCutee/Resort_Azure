<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Realistic Airplane Seating Chart</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .seat {
      width: 60px;
      height: 60px;
      margin: 5px;
      text-align: center;
      line-height: 60px;
      border: 2px solid #000;
      border-radius: 5px;
      cursor: pointer;
      position: relative;
      font-size: 14px;
      display: inline-block;
    }

    .economy {
      background-color: lightgray;
    }

    .business {
      background-color: lightblue;
    }

    .first-class {
      background-color: lightgoldenrodyellow;
    }

    .occupied {
      background-color: gray;
      cursor: not-allowed;
    }

    .aisle {
      width: 20px;
      height: 60px;
      margin: 5px;
      background-color: #f2f2f2;
      display: inline-block;
    }

    .window {
      background-color: lightcyan;
    }

    .seat input[type="checkbox"] {
      display: none;
    }

    .seat input[type="checkbox"]:checked + label {
      background-color: red; /* Change to red when checked */
      color: white; /* Make text white */
    }

    .seat label {
      display: block;
      height: 100%;
      width: 100%;
      line-height: 60px;
      text-align: center;
      font-weight: bold;
      cursor: pointer;
    }

    .seat input[type="checkbox"]:not(:checked) + label {
      background-color: inherit;
      color: inherit;
    }

    .row {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .class-label {
      margin-top: 10px;
      font-weight: bold;
    }
    .class-label {
  display: flex;
  align-items: center;
  gap: 10px; /* Space between the colored boxes and text */
}

.class-label1 span {
  display: inline-flex;
  align-items: center;
}

.economy-class1 {
  width: 20px;
  height: 20px;
  background-color: lightgray;
  border-radius: 2px; /* Optional: for rounded corners */
}

.business-class1 {
    width: 20px;
    height: 20px;
  background-color: lightblue;
  border-radius: 2px; /* Optional: for rounded corners */
}

.first-class1 {
    width: 20px;
    height: 20px;
  background-color: lightgoldenrodyellow;
  border-radius: 2px; /* Optional: for rounded corners */
}

  </style>
</head>
<body>
  <div class="container">
    <h2 class="text-center my-4">Realistic Airplane Seating Chart</h2>

    <!-- Economy Class -->
    <div class="row">
      <div class="col-12 text-center">
        <h4>Economy Class</h4>
        <div class="row justify-content-center">
          <!-- Row 1 to 10 -->
          <div class="seat economy window">
            <input type="checkbox" id="1A" />
            <label for="1A">1A</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="1B" />
            <label for="1B">1B</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="1C" />
            <label for="1C">1C</label>
          </div>
          <div class="aisle"></div>
          <div class="seat economy">
            <input type="checkbox" id="1D" />
            <label for="1D">1D</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="1E" />
            <label for="1E">1E</label>
          </div>
          <div class="seat economy window">
            <input type="checkbox" id="1F" />
            <label for="1F">1F</label>
          </div>
        </div>
        <div class="row justify-content-center">
          <!-- Row 2 to 10 -->
          <div class="seat economy window">
            <input type="checkbox" id="2A" />
            <label for="2A">2A</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="2B" />
            <label for="2B">2B</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="2C" />
            <label for="2C">2C</label>
          </div>
          <div class="aisle"></div>
          <div class="seat economy">
            <input type="checkbox" id="2D" />
            <label for="2D">2D</label>
          </div>
          <div class="seat economy">
            <input type="checkbox" id="2E" />
            <label for="2E">2E</label>
          </div>
          <div class="seat economy window">
            <input type="checkbox" id="2F" />
            <label for="2F">2F</label>
          </div>
        </div>
        <!-- More Rows can follow for Economy class -->
      </div>
    </div>

    <!-- Business Class -->
    <div class="row">
      <div class="col-12 text-center">
        <h4>Business Class</h4>
        <div class="row justify-content-center">
          <!-- Seats in Business Class -->
          <div class="seat business window">
            <input type="checkbox" id="12A" />
            <label for="12A">12A</label>
          </div>
          <div class="seat business">
            <input type="checkbox" id="12B" />
            <label for="12B">12B</label>
          </div>
          <div class="seat business">
            <input type="checkbox" id="12C" />
            <label for="12C">12C</label>
          </div>
          <div class="aisle"></div>
          <div class="seat business">
            <input type="checkbox" id="12D" />
            <label for="12D">12D</label>
          </div>
          <div class="seat business">
            <input type="checkbox" id="12E" />
            <label for="12E">12E</label>
          </div>
          <div class="seat business window">
            <input type="checkbox" id="12F" />
            <label for="12F">12F</label>
          </div>
        </div>
      </div>
    </div>

    <!-- First Class -->
    <div class="row">
      <div class="col-12 text-center">
        <h4>First Class</h4>
        <div class="row justify-content-center">
          <!-- Seats in First Class -->
          <div class="seat first-class window">
            <input type="checkbox" id="16A" />
            <label for="16A">16A</label>
          </div>
          <div class="seat first-class">
            <input type="checkbox" id="16B" />
            <label for="16B">16B</label>
          </div>
          <div class="seat first-class">
            <input type="checkbox" id="16C" />
            <label for="16C">16C</label>
          </div>
          <div class="aisle"></div>
          <div class="seat first-class">
            <input type="checkbox" id="16D" />
            <label for="16D">16D</label>
          </div>
          <div class="seat first-class window">
            <input type="checkbox" id="16E" />
            <label for="16E">16E</label>
          </div>
        </div>
      </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="class-label1">
            <span class="economy-class1"></span><span> Economy Class</span><br>
            <span class="business-class1"></span><span> Business Class</span><br>
            <span class="first-class1"></span><span> First Class</span><br>
            
            </div>
        </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
