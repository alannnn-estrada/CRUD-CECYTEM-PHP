document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();
    var formData = new FormData(this);

    fetch('../php/registro.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            var messageDiv = document.getElementById("message");
            if (data.status === "success") {
                messageDiv.className = "alert alert-success";
                document.getElementById("registerForm").reset();
            } else {
                messageDiv.className = "alert alert-error";
            }
            messageDiv.textContent = data.message;
            messageDiv.style.display = "block";

            setTimeout(function () {
                messageDiv.style.display = "none";
            }, 3000);
        })
        .catch(error => {
            console.error('Error:', error);
        });
});