document.addEventListener("DOMContentLoaded", function () {
    const taskForm = document.getElementById("taskForm");
    const sucessMessage = document.getElementById("sucessMessage");
    const taskList = document.getElementById("taskList");

    taskForm.addEventListener("submit", function (e) {
        e.preventDefault();

        var formData = new FormData(taskForm);
        const taskID = taskForm.getAttribute("data-task-id");

        let url = "../php/crear-tarea.php";
        if (taskID) {
            url = "../php/editar-tarea.php";
            formData.append("id", taskID);
        }

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    sucessMessage.textContent = data.message;
                    sucessMessage.style.display = "block";
                    setTimeout(() => {
                        sucessMessage.style.display = "none";
                    }, 3000);

                    taskForm.reset();
                    taskForm.removeAttribute("data-task-id");
                    taskForm.querySelector("button[type=submit]").textContent = "Crear Tarea";

                    fetchAndDisplayTasks();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message,
                    });
                }
            }).catch(error => {
                console.error(error);
            });
    });

    function fetchAndDisplayTasks() {
        fetch("../php/obtener-tareas.php")
            .then(response => response.json())
            .then(data => {
                taskList.innerHTML = '';
                data.forEach(task => {
                    const taskItem = document.createElement('div');
                    const dateTask = new Date(task.fecha_vencimiento);
                    let classicTask = "verde";
                    if (dateTask < new Date().setHours(0, 0, 0, 0)) {
                        classicTask = "rojo";
                    } else if (dateTask < new Date(new Date().setDate(new Date().getDate() + 1))) {
                        classicTask = "amarillo";
                    }
                    taskItem.classList.add('task-item', classicTask);

                    taskItem.innerHTML = `
                        <div class="task-content">
                            <div class="task-title">${task.titulo}</div>
                            <div class="task-description">${task.descripcion}</div>
                            <div class="task-due">Fecha de Vencimiento: ${task.fecha_vencimiento}</div>
                        </div>
                        <div class="task-actions">
                            <button class="edit btn-editar" data-task-id="${task.id}">Editar Tarea</button>
                            <button class="delete btn-eliminar" data-task-id="${task.id}">Eliminar Tarea</button>
                        </div>
                    `;

                    taskList.appendChild(taskItem);
                });
            }).catch(error => {
                console.error(error);
            });
    }

    taskList.addEventListener("click", function (e) {
        if (e.target.classList.contains("btn-eliminar")) {
            const taskID = e.target.dataset.taskId;

            Swal.fire({
                title: '¿Estás seguro?',
                text: "No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, bórralo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append("taskID", taskID);

                    fetch("../php/eliminar-tarea.php", {
                        method: 'POST',
                        body: formData
                    }).then(response => response.json())
                        .then(data => {
                            if (data.status === "success") {
                                e.target.closest(".task-item").remove();
                                sucessMessage.textContent = data.message;
                                sucessMessage.style.display = "block";

                                setTimeout(() => {
                                    sucessMessage.style.display = "none";
                                }, 3000);
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: data.message,
                                });
                            }
                        }).catch(error => {
                            console.error(error);
                        });
                }
            });
        } else if (e.target.classList.contains("btn-editar")) {
            const taskID = e.target.dataset.taskId;
            const taskTitle = e.target.closest(".task-item").querySelector(".task-title").textContent;
            const taskDescription = e.target.closest(".task-item").querySelector(".task-description").textContent;
            const taskDueDate = e.target.closest(".task-item").querySelector(".task-due").textContent.split(": ")[1];

            console.log('Task ID:', taskID);
            console.log('Task Title:', taskTitle);
            console.log('Task Description:', taskDescription);
            console.log('Task Due Date:', taskDueDate);

            document.getElementById("title").value = taskTitle;
            document.getElementById("description").value = taskDescription;
            document.getElementById("dueDate").value = taskDueDate;

            taskForm.action = "../php/editar-tarea.php";
            taskForm.setAttribute("data-task-id", taskID);
            taskForm.querySelector("button[type=submit]").textContent = "Guardar cambios";
        }
    });

    fetchAndDisplayTasks();
});

const logoutButton = document.getElementById("logoutBtn");
logoutButton.addEventListener("click", function () {
    window.location.href = "../php/cerrar-sesion.php";
});
