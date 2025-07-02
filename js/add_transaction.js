// Ожидание полной загрузки DOM перед выполнением скрипта
document.addEventListener("DOMContentLoaded", function () {
  // Подгрузка категорий для выпадающего списка формы транзакции
  fetch("../includes/get_categories.php")
    .then((res) => res.json())
    .then((categories) => {
      // Получение элемента select для категорий
      const select = document.querySelector('select[name="category_id"]');
      // Заполнение select опциями из полученных категорий
      categories.forEach((cat) => {
        const opt = document.createElement("option");
        opt.value = cat.id;
        opt.textContent = cat.name;
        select.appendChild(opt);
      });
    });

  // Отправка формы добавления транзакции
  const form = document.getElementById("transactionForm");
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Предотвратить стандартную отправку формы

    // Сбор данных формы
    const formData = new FormData(form);

    // Отправка данных на сервер для добавления транзакции
    fetch("../includes/add_transaction.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        // Обработка ответа от сервера
        if (data.success) {
          // Если успешно добавлено, перенаправить на дашборд
          window.location.href = "dashboard.html";
        } else if (data.error === "not_auth") {
          // Если пользователь не авторизован, перенаправить на страницу входа
          window.location.href = "login.html";
        } else {
          // В случае других ошибок показать сообщение
          alert("Ошибка при добавлении операции!");
        }
      });
  });
});
