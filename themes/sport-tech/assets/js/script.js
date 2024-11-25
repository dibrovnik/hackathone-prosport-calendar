document.addEventListener("DOMContentLoaded", () => {
  const burgerIcon = document.querySelector(".__burger_icon");
  const extraButtons = document.querySelector(".__extra_buttons");
  const headerIcon = document.querySelector(".header-icon");
  const togglePassword = document.getElementById("togglePassword");
  const passwordInput = document.getElementById("pas");
  const filterCalendar = document.getElementById("filterCalendar");
  const filterCalendarScreen = document.querySelector(".filter-calendar");
  const filterClose = document.querySelector(".close-filters");
  const filterMenu = document.querySelector(".filter-menu");

  if (burgerIcon) {
    burgerIcon.addEventListener("click", () => {
      burgerIcon.classList.toggle("open");
      extraButtons.classList.toggle("open");
      headerIcon.classList.toggle("open");
    });
  }

  if (togglePassword) {
    togglePassword.addEventListener("click", () => {
      // Меняем тип поля с пароля на текст и наоборот
      if (passwordInput.type === "password") {
          passwordInput.type = "text";
          togglePassword.innerHTML = `
              <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M7.99939 2.43826C5.72215 2.43826 3.53248 3.93876 2.29643 5.99988C1.91109 6.56597 1.91109 7.31014 2.29643 7.87622C3.53245 9.83772 5.68174 11.0338 7.99931 11.0509C10.3168 11.0338 12.4661 9.83772 13.7022 7.87622C14.0875 7.31014 14.0875 6.56597 13.7022 5.99988C12.4662 3.93876 10.3169 2.43826 7.99939 2.43826Z" fill="white"/>
              </svg>
          `;
      } else {
          passwordInput.type = "password";
          togglePassword.innerHTML = `
              <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M15.3588 4.42264C13.7398 1.92479 10.9758 0.405611 7.99931 0.377686C5.02283 0.405611 2.25883 1.92479 0.639877 4.42264C-0.213292 5.67472 -0.213292 7.32137 0.639877 8.57348C2.25791 11.0729 5.02208 12.5936 7.99934 12.6225C10.9758 12.5945 13.7398 11.0753 15.3588 8.5775C16.2138 7.32448 16.2138 5.67563 15.3588 4.42264ZM13.7022 7.43826C12.4662 9.39876 10.3169 10.5949 7.99931 10.612C5.68177 10.5949 3.53248 9.39876 2.29643 7.43826C1.91109 6.87214 1.91109 6.12797 2.29643 5.56188C3.53245 3.60138 5.68174 2.40528 7.99931 2.3881C10.3168 2.40525 12.4661 3.60138 13.7022 5.56188C14.0875 6.12797 14.0875 6.87214 13.7022 7.43826Z" fill="white"/>
                  <path d="M7.99939 9.18042C9.47981 9.18042 10.6799 7.9803 10.6799 6.49988C10.6799 5.01945 9.47981 3.81934 7.99939 3.81934C6.51897 3.81934 5.31885 5.01945 5.31885 6.49988C5.31885 7.9803 6.51897 9.18042 7.99939 9.18042Z" fill="white"/>
              </svg>
          `;
      }
    });
  }

  

  if (filterCalendar) {
    filterCalendar.addEventListener("click", () => {
      filterCalendarScreen.classList.toggle('open');
    });
    if (filterClose) {
      filterClose.addEventListener("click", () => {
        filterCalendarScreen.classList.toggle('open');
      })
    }
  }

  if (filterMenu) {
    filterMenu.querySelectorAll('.menu-item').forEach((item) => {
      item.addEventListener("click", (e) => {
        // Получаем тип фильтра
        let filterType = item.getAttribute('data-filter-type');
        console.log(filterType);

        // Убираем класс 'selected' у всех пунктов меню
        filterMenu.querySelectorAll('.menu-item').forEach((el) => {
          el.classList.remove('selected');
        });

        // Добавляем класс 'selected' текущему элементу
        item.classList.add('selected');

        // Закрываем все панели фильтров
        document.querySelectorAll('.filter-options').forEach((el) => {
          el.classList.remove('open');
        });

        // Открываем соответствующую панель фильтров
        let targetFilter = document.querySelector(`.filter-options.${filterType}`);
        if (targetFilter) {
          targetFilter.classList.add('open');
        }
      });
    });
  }

  



  
  
});
