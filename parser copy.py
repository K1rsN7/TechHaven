import requests
from bs4 import BeautifulSoup
import json
import os

dict_category = {}

# Загрузка конфигурации из JSON файла
with open('pars_keys.json', 'r') as file:
    list_urls_and_keys = json.load(file)["list_urls_and_keys"]

def fetch_data(url, element, key):
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
        items = [item.get('data-src').split("/")[-1] for item in items if item.get('data-src') and 'products' in item.get('data-src')]
        return len(items), items

    # Для других элементов возвращаем текст
    return len(items), [item.get_text(strip=True) for item in items]

def save_results(name, results):
    # Создание директории для сохранения результатов, если она не существует
    os.makedirs('results', exist_ok=True)
    
    # Формирование имени файла
    file_path = f'results/{name}.json'
    
    # Запись результатов в JSON файл
    with open(file_path, 'w', encoding='utf-8') as f:
        json.dump(results, f, ensure_ascii=False, indent=4)

def parse_data():
    if not os.path.exists("./results"):
        os.makedirs("./results")
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
        
        if not os.path.exists(f"./results/{name}.json"):
            open(f"./results/{name}.json", 'a').close()
        with open(f"./results/{name}.json", 'w', encoding='utf-8') as json_file:
            json.dump(all_results, json_file, ensure_ascii=False, indent=4)

def characterizations_preprocessing(data):
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

def data_processing_first_step():
    for file in os.listdir("./results"):
        if file.endswith(".json"):
            with open(f"./results/{file}", 'r', encoding='utf-8') as f:
                data = json.load(f)
            
            # Обработка категорий
            try:
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
                        with open(f"./results/{file}", 'w', encoding='utf-8') as f:
                            json.dump(rows, f, ensure_ascii=False, indent=4)
                else:
                    if len(data.keys()) == 4:
                        print(f"Обработка файла {file}...")
                        images = [item for item in data["image_product"] if  len(item.split('/')[-1].split('.')) == 3]
                        name_product = data["name_product"]
                        characterizations = data["characterizations"]
                        delivery_days, id_brands, guarantee_months = characterizations_preprocessing(characterizations)
                        price_product = data["price_product"]
                        all_rows = []
                        for item in range(0, len(name_product)):
                            rows = {}
                            rows["image"] = images[item]
                            rows["name_product"] = name_product[item]
                            rows["description"] = ""
                            rows["delivery_days"] = int(delivery_days[item])
                            rows["price"] = int(price_product[item][:-1].replace(" ", ""))
                            rows["id_category"] = 0
                            rows["id_brand"] = id_brands[item]
                            rows["guarantee_months"] = int(guarantee_months[item].split()[0])
                            all_rows.append(rows)
                    with open(f"./results/{file}", 'w', encoding='utf-8') as json_file:
                        json.dump(all_rows, json_file, ensure_ascii=False, indent=4)
            except:
                pass

def data_processing_second_step():
    all_brands = []
    print("Получение всех брендов")
    for file in os.listdir("./results"):
        if file.endswith(".json"):
            with open(f"./results/{file}", 'r', encoding='utf-8') as f:
                data = json.load(f)
                if "id_brand" in data[0].keys():
                    for row in data:
                        all_brands.append(row["id_brand"])
    print("Формирование словаря брендов")
    all_brands = set(all_brands)
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
    
    for file in os.listdir("./results"):
        if file.endswith(".json"):
            with open(f"./results/{file}", 'r', encoding='utf-8') as f:
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
                        with open(f"./results/{file}", 'w', encoding='utf-8') as json_file:
                            json.dump(all_rows, json_file, ensure_ascii=False, indent=4)
    
    brand_keys = sorted(brand_dict, key=brand_dict.get)
    brand_data = []
    for key in brand_keys:
        brand_data.append({"name_brand": key})

    with open(f"./results/brand.json", 'w', encoding='utf-8') as json_file:
        json.dump(brand_data, json_file, ensure_ascii=False, indent=4)
    
    print("Добавлен файл brand.json")

def data_processing_third_step():
    files = [file for file in os.listdir("./results") if file not in ['brand.json', 'category.json']]
    all_data = []
    for file in files:
        with open(f"./results/{file}", 'r', encoding='utf-8') as f:
            data = json.load(f)
            all_data.extend(data)
    
    with open(f"./results/product.json", 'w', encoding='utf-8') as json_file:
        json.dump(all_data, json_file, ensure_ascii=False, indent=4)
    
    print("Добавлен файл product.json")
    
if __name__ == "__main__":
    parse_data()
    data_processing_first_step()
    data_processing_second_step()
    data_processing_third_step()