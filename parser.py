import hashlib
import requests
from bs4 import BeautifulSoup
import json
import os
import g4f
from tqdm import tqdm
import pandas as pd
from sqlalchemy import create_engine
from faker import Faker
import random
from datetime import datetime, timedelta

# Подключение к базе данных
with open("./config.json", "r") as f:
        data = json.load(f)
        DB_USERNAME = data["DB_USER"]
        DB_PASSWORD = data["DB_PASSWORD"]
        HOSTNAME = f"{data["DB_HOST"]}:{data["DB_PORT"]}"
        DATABASE_NAME = data["DB_NAME"]
ENGINE = create_engine(f"mysql+pymysql://{DB_USERNAME}:{DB_PASSWORD}@{HOSTNAME}/{DATABASE_NAME}")

dict_category = {} # Словарь категорий

# Загрузка конфигурации из JSON файла
with open('pars_keys.json', 'r') as file:
    list_urls_and_keys = json.load(file)["list_urls_and_keys"]

def fetch_data(url:str, element:str, key:str, is_brands:bool=False) -> tuple[int, list[str]]:
    """Получение текста, который содержит конкретный элемент на странице

    Args:
        url (str): ссылка на сайт
        element (str): элемент HTML кода
        key (str): класс элемента HTML кода
        is_brands (bool): пытаемся ли мы получить бренды

    Returns:
        count (int): количество найденных элементов на странице
        items (list[str]): текст найденного элемента
    """
    try:
        response = requests.get(url)
        response.raise_for_status()  # Генерирует исключение для ошибок HTTP
    except requests.RequestException as e:
        print(f"Ошибка при запросе {url}: {e}")
        return 0, []

    soup = BeautifulSoup(response.text, 'html.parser')
    items = soup.find_all(element, class_=key)
    print(f"Найдено элементов {url} для {key}: {len(items)}") if items else None
    
    if element == "img":
        # Возвращаем список значений атрибута src
        if not is_brands:
            items = [item.get('data-src').split("/")[-1] for item in items if item.get('data-src') and 'products'  in item.get('data-src')]
        else:
            items = {item.get('alt'): item.get('data-src').split("/")[-1] for item in items if item.get('data-src') and 'brands' in item.get('data-src')}
        return len(items), items
    # Для других элементов возвращаем текст
    return len(items), [item.get_text(strip=True) for item in items]

def parse_data():
    """Получение информации с сайта в соответствии с ключами файла pars_keys.json
    """
    if not os.path.exists("./data"):
        os.makedirs("./data")
    for item in list_urls_and_keys:
        name = item['name']
        is_pages = bool(item['is_pages'])
        all_results = {}  # Список для хранения всех результатов

        for data in item['data']:
            url = data['url']
            for element_info in data['elements']:
                field = element_info['field']
                element = element_info['element']
                key = element_info['key']
                
                print(f"======Данные для {name}: {url}======")
                
                # Продукция размещается не на одной странице
                if is_pages:
                    for page in range(1, 25):
                        current_url = f"{url}?page={page}" if page != 1 else url
                        count, results = fetch_data(current_url, element, key)
                        if count == 0:
                            break  # Прекращаем, если нет элементов
                        # Добавляем результаты в общий список
                        if field not in all_results.keys():
                            all_results[field] = []
                        all_results[field].extend(results)
                else:
                    _, results = fetch_data(url, element, key)
                    all_results[field] = results  # Добавляем результаты в общий список
                    break  # Выход из цикла после первого запроса
        
        # Сохранение результатов в JSON файл
        if not os.path.exists(f"./data/{name}.json"):
            open(f"./data/{name}.json", 'a').close()
        with open(f"./data/{name}.json", 'w', encoding='utf-8') as json_file:
            json.dump(all_results, json_file, ensure_ascii=False, indent=4)

def characterizations_preprocessing(data: list[str]) -> tuple[list[str], list[str], list[str]]:
    """Обработка сплошного массива данных содержащие внутри себя информацию об количестве дней доставки продукта, его бренде и гарантийным сроком

    Args:
        data (list[str]): список данных с количеством дней доставок, брендом и гарантийным сроком

    Returns:
        delivery_days (list[str]): список с количеством дней необходимых на доставку
        brands (list[str]): список брендов продуктов
        guarantee_month (list[str]): список количества месяцев гарантийного срока продукции
    """
    delivery_days = []
    brands = []
    guarantee_months = []

    i = 0
    # Обработка данных
    while i < len(data):
        delivery_days.append(data[i])  # Количество дней доставки
        
        # Проверка наличия бренда
        if i + 1 < len(data) and "мес." not in data[i + 1] :  # Если есть бренд
            brands.append(data[i + 1])
            guarantee_months.append(data[i + 2])
            i += 3
        else:  # Если нет бренда
            brands.append(None)
            guarantee_months.append(data[i + 1])
            i += 2
    return delivery_days, brands, guarantee_months

def generate_product_description(product_name:str) -> str:
    """Генерация описания к продукту с использованием GPT-4o 

    Args:
        product_name (str): название продукта

    Returns:
        responce (str): _текст описания продукта
    """
    # Формирование запроса
    messages = [
        {"role": "user", "content": f"Сгенерируй описание для товара: {product_name}."}
    ]
    
    # Выполнение запроса и получение ответа
    response = g4f.ChatCompletion.create(
        model=g4f.models.gpt_4o,  # Используем модель GPT-4o
        messages=messages
    )
    return response

def process_category_and_product_data() -> None:
    """Разбиение общей информации на отдельные записи
    """
    # Чтение всех файлов 
    for file in os.listdir("./data"):
        if file.endswith(".json"):
            with open(f"./data/{file}", 'r', encoding='utf-8') as f:
                data = json.load(f)
            try:
                # Обработка категорий
                if len(data.keys()) == 1:
                    print(f"Обработка файла {file}...")
                    
                    # Изменяем данные
                    data = data["category"]
                    if "Гарнитуры" in data:
                        data.remove("Гарнитуры")
                        rows = [{"name_category": name_category} for name_category in data]
                        for id_category, name_category in enumerate(data, start=1):
                            dict_category[str(name_category)] = id_category
                        
                        # Запись изменений обратно в файл
                        with open(f"./data/{file}", 'w', encoding='utf-8') as f:
                            json.dump(rows, f, ensure_ascii=False, indent=4)
                else:
                    # Обработка продуктов 
                    if len(data.keys()) == 4:
                        print(f"Обработка файла {file}...")
                        images = [item for item in data["image_product"] if  len(item.split('/')[-1].split('.')) == 3] 
                        name_product = data["name_product"] 
                        characterizations = data["characterizations"]
                        delivery_days, id_brands, guarantee_months = characterizations_preprocessing(characterizations)
                        price_product = data["price_product"]
                        
                        # Исправление
                        all_rows = []
                        for item in tqdm(range(0, len(name_product)), desc=f"Обработка данных файла {file}"):
                            rows = {}
                            rows["image"] = images[item]
                            rows["name_product"] = name_product[item]
                            rows["description"] = generate_product_description(name_product[item])
                            rows["delivery_days"] = int(delivery_days[item])
                            rows["price"] = int(price_product[item][:-1].replace(" ", ""))
                            rows["id_category"] = 0
                            rows["id_brand"] = id_brands[item]
                            rows["guarantee_months"] = int(guarantee_months[item].split()[0])
                            all_rows.append(rows)
                    with open(f"./data/{file}", 'w', encoding='utf-8') as json_file:
                        json.dump(all_rows, json_file, ensure_ascii=False, indent=4)
            except:
                pass

def process_brands_in_records() -> None:
    """Обработка брендов в записях
    """
    all_brands = [] # Список всех брендов продуктов
    
    # Получение всех брендом продуктов
    for file in os.listdir("./data"):
        if file.endswith(".json"):
            with open(f"./data/{file}", 'r', encoding='utf-8') as f:
                data = json.load(f)
                if "id_brand" in data[0].keys():
                    for row in data:
                        all_brands.append(row["id_brand"])
    all_brands = set(all_brands)
    
    # Создание словаря для индексирования брендов
    brand_dict = {}
    for id_brand, name_brand in enumerate(all_brands, start=1):
        brand_dict[str(name_brand)] = id_brand
    key_old = None
    key_value = None
    for key, value in brand_dict.items():
        if value == 1:
            key_old = key
        
        if key == "None":
            key_value = value
    brand_dict["None"] = 1
    brand_dict[key_old] = key_value
    
    # Изменение всех названий бренда на индексы в записях
    for file in os.listdir("./data"):
        if file.endswith(".json"):
            with open(f"./data/{file}", 'r', encoding='utf-8') as f:
                data = json.load(f)
                if "id_brand" in data[0].keys():
                    if isinstance(data[0]["id_brand"], str):
                        print(f"Поправлен бренды в файле {file}")
                        all_rows = []
                        id_category = dict_category[file.split(".")[0]]
                        for row in data:
                            key = brand_dict[str(row["id_brand"])]
                            row["id_brand"] = key if key else 1
                            row["id_category"] = id_category
                            all_rows.append(row)
                        with open(f"./data/product_{file}", 'w', encoding='utf-8') as json_file:
                            json.dump(all_rows, json_file, ensure_ascii=False, indent=4)
    
    # Формирование файла для последующего импорта брендов в базу данных
    brand_keys = sorted(brand_dict, key=brand_dict.get)
    brand_data = []
    for key in brand_keys:
        brand_data.append({"name_brand": key})
    with open(f"./data/brand.json", 'w', encoding='utf-8') as json_file:
        json.dump(brand_data, json_file, ensure_ascii=False, indent=4)
    print("Добавлен файл brand.json")

def correct_brands() -> None:
    """Добавление к каждому бренду фотографии
    """
    # Получение названий фотографий брендов
    _, results_img = fetch_data("https://28bit.ru/brands/", "img", "lazy-img", is_brands=True)
    
    # Читаем старые значения брендов
    with open('./data/brand.json', 'r', encoding='utf-8') as file:
        data = json.load(file)

    # Обновляем записи с брендами
    new_data = []
    for row in tqdm(data, desc="Обновление брендов"):
        brand_name = row["name_brand"]
        row_new = {}
        if brand_name != "28Bit":
            row_new["name_brand"] = brand_name
            row_new["image_brand"] = results_img[brand_name] if brand_name in results_img.keys() else "dummy200.png"       
        else:
            row_new["name_brand"] = "Tech Haven"
            row_new["image_brand"] = "tech_haven.webp"
        new_data.append(row_new)
    
    # Записываем результаты в файл
    with open(f"./data/brand.json", 'w', encoding='utf-8') as json_file:
        json.dump(new_data, json_file, ensure_ascii=False, indent=4)

def import_data_from_json(file_path:str, table_name:str) -> None:
        """Чтение данных из файла json и последующий импорт их

        Args:
            file_path (str): путь до файла
            table_name (str): название таблицы в базе данных
        """
        
        df = pd.read_json(file_path)
        df.to_sql(table_name, con=ENGINE, if_exists='append', index=False)

def generate_user() -> None:
    """Генерация записей с пользователями
    """
    # Получаем список всех изображений в папке
    image_folder = './img/people'
    image_files = os.listdir(image_folder)
    emails = [] # Для генерации уникальных почт
    faker = Faker()
    
    data = [] # Все записи в базу данных
    
    for i in range(len(image_files)):
        username = " ".join([faker.last_name_male(), faker.name_male()])
        image_user = image_files[i]
        while True:
            email = faker.email()
            if email not in emails:
                emails.append(email)
                break
        password = faker.password().encode("utf8")
        password = hashlib.md5(password).hexdigest()
        role = 'customer'
        user_data = {
            "username": username,
            "image_user": image_user,
            "email": email,
            "password": password,
            "role": role
        }
        data.append(user_data)
        
    with open(f"./data/user.json", 'w', encoding='utf-8') as json_file:
        json.dump(data, json_file, ensure_ascii=False, indent=4)
    
    print("Созданы пользователи сайта")

def generate_review() -> None:
    """Генерация отзывов о магазине
    """
    image_folder = './img/people'
    image_files = os.listdir(image_folder)
    def generate_random_date(start_date:datetime, end_date:datetime) -> datetime:
        """Генерация случайной даты в диапазоне

        Args:
            start_date (datetime): начальная дата
            end_date (datetime): конечная дата
        """
        # Вычисляем разницу между датами
        delta = end_date - start_date
        # Генерируем случайное количество дней в диапазоне
        random_days = random.randint(0, delta.days)
        # Возвращаем случайную дату
        return start_date + timedelta(days=random_days)
    
    data = []
    for i in tqdm(range(len(image_files)), desc="Генерация отзывов..."):
        rating = random.randint(4, 5)
        messages = [
            {"role": "user", "content": f"Сгенерируй отзыв на русском о сайте не более 250 символов. Информация о сайте: Tech Haven - интернет магазин компьютеров и комплектующих по низкой цене.  Самовывоз в день заказа. Быстрая доставка по Нижнему Новгороду. "}
        ]
        # Выполнение запроса и получение ответа
        comment = g4f.ChatCompletion.create(
            model=g4f.models.gpt_4o,  # Используем модель GPT-4o
            messages=messages
        )
        review_data = {
            "id_user": i+1,
            "rating": rating,
            "comment": comment,
            "review_date": generate_random_date(datetime(2023, 1, 1),  datetime(2024, 10, 31)).strftime('%Y-%m-%d'),
        }
        data.append(review_data)

    with open(f"./data/review.json", 'w', encoding='utf-8') as json_file:
        json.dump(data, json_file, ensure_ascii=False, indent=4)

def import_data_in_database() -> None:
    """Внесение всех записей из файлов директории data в базу данных
    """
    
    # Добавление всех записей в базу данных
    for file in sorted(os.listdir("./data")):
        if file.startswith("product_"):
            import_data_from_json(f"./data/{file}", "product")
        elif file.startswith("user"):
            import_data_from_json(f"./data/{file}", "user")
            import_data_from_json(f"./data/review.json", "review")
            print(f"Импортированы данные из review.json в базу данных")
        elif file.startswith("review"):
            continue
        else:
            import_data_from_json(f"./data/{file}", file.split(".")[0])
        print(f"Импортированы данные из {file[:-5]} в базу данных")

if __name__ == "__main__":
    parse_data()
    process_category_and_product_data()
    process_brands_in_records()
    correct_brands()
    generate_user()
    generate_review()
    import_data_in_database()