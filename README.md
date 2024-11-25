# Prosport Calendar

### Prosport Calendar — экосистема, разработанная для эффективного мониторинга и управления спортивными мероприятиями. Наша платформа включает в себя Web- приложение и Telegram-бота, обеспечивая пользователям полный контроль над информацией о спортивных событиях.

<p align="center">
    <img src="https://dev-level.ru/storage/app/media/diagram.png" alt="Diagram"/>
</p>

На данном этапе 23.11.2024 2:43 уже реализован парсер, REST API для общения с фронтендом и телеграмм ботом, а также дизайн всего сервиса.
В качестве стека выбран PHP для веба и Python для бота уведомлений.
Для упрощения работы с фронтендом применена OctoberCMS, на ней же поднят сервис Rest API. Отдельно на сервере запущены сервисы написанные на Python.

Основной сайт: https://dev-level.ru/

Бот для уведомлений: https://t.me/SportTexNotification_bot

API Документация: https://documenter.getpostman.com/view/37107310/2sAYBUDC87

Ссылка на папку (скринкасты, презентация, защита): https://drive.google.com/drive/folders/1tjjYBPDUEOk_dteX2sM_qiZbvaa1lUn7?usp=drive_link

Защита: https://drive.google.com/file/d/1p0xjR15bZnIQoTinq3PlZqqG0Oau7IT_/view?usp=drive_link

Скринкаст тг: https://drive.google.com/file/d/1jUJioOERMEcTujq4BbK-_n1IIlID7WwE/view?usp=drive_link

Скринкаст веб: https://drive.google.com/file/d/14MzPqEgREn4P9dhwVUGVTn9PYToksxBn/view?usp=drive_link

Презентация: https://docs.google.com/presentation/d/1WdngntWbP70_486B3L7p8jpVjpMpxTlB/edit?usp=drive_link&ouid=104525858517934154863&rtpof=true&sd=true

Этот проект создан с использованием [October CMS](https://octobercms.com/) — мощной и гибкой системы управления контентом, построенной на базе Laravel. Проект предлагает масштабируемое и удобное решение для управления контентом с использованием модульной архитектуры и богатого функционала.

## Установка

1. **Установите october**
   Инструкция https://docs.octobercms.com/3.x/setup/installation.html#installing-october-cms

2. **Скопируйте папки plugins и themes/sport-tech**

4. **Запустите миграции**
   ```bash
   php artisan october:up
   ```

5. **Запустите локальный сервер**
   ```bash
   php artisan serve
   ```
   Откройте проект в браузере по адресу: `http://localhost:8000`.

---

## Использование

### Панель управления
Панель администратора доступна по адресу:
```
https://dev-level.ru/admin
```

Стандартные данные для входа:
- **Логин**: `*********`
- **Пароль**: `*********`

## Разработка

### Структура проекта
- `plugins/`: Пользовательские и сторонние плагины.
- `themes/`: Темы для управления внешним видом.
- `config/`: Конфигурационные файлы.
- `storage/`: Логи, кэш и временные файлы.

### Основные команды
- **Запуск миграций**: `php artisan october:up`
- **Откат миграций**: `php artisan october:down`
- **Очистка кэша**: `php artisan cache:clear`

---

## Требования

- PHP 8.0 или выше
- Composer
- MySQL

---
