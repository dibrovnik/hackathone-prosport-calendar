from flask import Flask, request, jsonify
import time
from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
from datetime import datetime
import os
import requests

app = Flask(__name__)

@app.route('/download', methods=['POST'])
def download_file():
    try:
        # Получение URL из запроса
        data = request.json
        page_url = data.get("page_url", "https://www.minsport.gov.ru/activity/government-regulation/edinyj-kalendarnyj-plan/")
        download_dir = "./downloads"
        os.makedirs(download_dir, exist_ok=True)

        # Настройки Selenium
        chrome_options = Options()
        chrome_options.add_argument("--headless")  # Запуск в фоновом режиме
        chrome_options.add_argument("--disable-gpu")
        chrome_options.add_argument("--no-sandbox")

        current_year = datetime.now().year

        # Установка драйвера
        driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=chrome_options)

        try:
            # Открываем страницу
            driver.get(page_url)
            wait = WebDriverWait(driver, 20)
            time.sleep(3)

            # Поиск аккордеона с текущим годом
            accordion = wait.until(
                EC.element_to_be_clickable((By.XPATH, f"//div[contains(@class, 'folder-text') and text()='{current_year}']"))
            )
            accordion.click()
            time.sleep(2)

            # Поиск ссылки на документ
            download_link = wait.until(
                EC.presence_of_element_located((By.XPATH, "(//*[contains(text(), 'Единый календарный план межрегиональных, всероссийских и международных физкультурных мероприятий')])[3]/ancestor::div[contains(@class, 'file-item')]//a[contains(text(), 'Скачать документ')]"))
            )

            link = download_link.get_attribute("href")

            # Скачиваем файл
            headers = {
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"
            }
            response = requests.get(link, stream=True, verify=False, headers=headers)
            response.raise_for_status()

            file_name = os.path.join(download_dir, f"EKP-{current_year}.pdf")
            # file_name = f"EKP-{current_year}.pdf"
            with open(file_name, "wb") as file:
                for chunk in response.iter_content(chunk_size=8192):
                    file.write(chunk)

            return jsonify({"status": "success", "file_path": file_name}), 200

        except Exception as e:
            return jsonify({"status": "error", "message": str(e)}), 500

        finally:
            driver.quit()

    except Exception as e:
        return jsonify({"status": "error", "message": str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
