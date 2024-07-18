const inputs = document.querySelectorAll(".input");
var messageDiv = document.getElementById("message");

function addcl() {
    let parent = this.parentNode.parentNode;
    parent.classList.add("focus");
}

function remcl() {
    let parent = this.parentNode.parentNode;
    if (this.value == "") {
        parent.classList.remove("focus");
    }
}

inputs.forEach((input) => {
    input.addEventListener("focus", addcl);
    input.addEventListener("blur", remcl);
});

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            return response.json();
        }

        return response.json().then(json => {
            throw new Error(json.message);
        });
    }).then(data => {
        if (data.status === "success") {
            messageDiv.className = "alert alert-success";
            setTimeout(() => {
                window.location.href = "tareas.php";
            }, 1000);
        } else {
            messageDiv.className = "alert alert-error";
        }
        messageDiv.textContent = data.message;
        messageDiv.style.display = "block";

        setTimeout(() => {
            messageDiv.style.display = "none";
        }, 3000);
    }).catch(error => {
        messageDiv.className = "alert alert-error";
        messageDiv.textContent = error.message;
        messageDiv.style.display = "block";

        setTimeout(() => {
            messageDiv.style.display = "none";
        }, 3000);
    })
});
