document.addEventListener("DOMContentLoaded", function () {
  let today = new Date();
  let formattedCheckinDate = today.toISOString().split("T")[0];
  document.getElementById("ngaydi").value = formattedCheckinDate;
  today.setDate(today.getDate() + 2);
  let formattedCheckoutDate = today.toISOString().split("T")[0];
  document.getElementById("ngayve").value = formattedCheckoutDate;

  const flightForm = document.getElementById("flightForm");
  flightForm.addEventListener("submit", function (event) {
    const tu = document.getElementById("tu").value;
    const den = document.getElementById("den").value;
    const ngaydi = new Date(document.getElementById("ngaydi").value);
    const ngayve = document.getElementById("ngayve").value
      ? new Date(document.getElementById("ngayve").value)
      : null;

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let hasError = false;

    if (tu === den) {
      showError(
        "error-tu",
        "Điểm bay xuất phát và điểm bay đến phải khác nhau!"
      );
      hasError = true;
    }

    if (ngaydi <= today) {
      showError("error-ngaydi", "Ngày đi phải lớn hơn ngày hiện tại!");
      hasError = true;
    }

    if (ngayve && ngayve < ngaydi) {
      showError("error-ngayve", "Ngày về không thể nhỏ hơn ngày đi!");
      hasError = true;
    }

    if (hasError) {
      event.preventDefault();
    }
  });
});

function showError(elementId, message) {
  const errorElement = document.getElementById(elementId);
  errorElement.textContent = message;
  errorElement.style.display = "block";

  setTimeout(function () {
    errorElement.style.display = "none";
  }, 1600);
}
document.querySelectorAll(".select-going").forEach((button) => {
  button.addEventListener("click", function () {
    document.querySelectorAll(".di").forEach((element) => {
      element.style.display = "none";
    });
    document.querySelectorAll(".ve").forEach((element) => {
      element.style.display = "block";
    });
  });
});
let selectedGoingFlight = null;
let selectedReturnFlight = null;
let goingFlightPrice = 0;
let returnFlightPrice = 0;
let goingSeatId = null;
let returnSeatId = null;

function selectFlight(id, type, price, seatId) {
  if (type === "Di") {
    selectedGoingFlight = id;
    goingFlightPrice = price;
    goingSeatId = seatId;
  } else if (type === "Ve") {
    selectedReturnFlight = id;
    returnFlightPrice = price;
    returnSeatId = seatId;
  }
  updateSubmitButtonState();
}

function updateSubmitButtonState() {
  const submitBtn = document.getElementById("submitBtn");
  if (selectedGoingFlight && selectedReturnFlight) {
    submitBtn.disabled = false;
  } else {
    submitBtn.disabled = true;
  }
}

function submitFlights() {
  if (selectedGoingFlight && selectedReturnFlight) {
    const form = document.getElementById("flightForm1");

    const goingInput = document.createElement("input");
    goingInput.type = "hidden";
    goingInput.name = "going_flight";
    goingInput.value = selectedGoingFlight;
    form.appendChild(goingInput);

    const returnInput = document.createElement("input");
    returnInput.type = "hidden";
    returnInput.name = "return_flight";
    returnInput.value = selectedReturnFlight;
    form.appendChild(returnInput);

    const goingPriceInput = document.createElement("input");
    goingPriceInput.type = "hidden";
    goingPriceInput.name = "going_price";
    goingPriceInput.value = goingFlightPrice;
    form.appendChild(goingPriceInput);

    const returnPriceInput = document.createElement("input");
    returnPriceInput.type = "hidden";
    returnPriceInput.name = "return_price";
    returnPriceInput.value = returnFlightPrice;
    form.appendChild(returnPriceInput);

    const goingSeatInput = document.createElement("input");
    goingSeatInput.type = "hidden";
    goingSeatInput.name = "going_seat_id";
    goingSeatInput.value = goingSeatId;
    form.appendChild(goingSeatInput);

    const returnSeatInput = document.createElement("input");
    returnSeatInput.type = "hidden";
    returnSeatInput.name = "return_seat_id";
    returnSeatInput.value = returnSeatId;
    form.appendChild(returnSeatInput);

    return true;
  } else {
    alert("Vui lòng chọn cả chuyến bay đi và chuyến bay về.");
    return false;
  }
}
