import csv
import re

SQL_PATH = '../resources/sql/kodewilayah2023.sql'
CSV_PATH = '../resources/csv/villages.csv'
OUTPUT_CSV_PATH = '../resources/csv/villages_with_postcode.csv'

def parse_sql_file(sql_path):
    mapping = {}

    with open(sql_path, encoding='utf-8') as f:
        content = f.read()

    # Regex untuk mengambil data dari INSERT INTO
    rows = re.findall(r"\('(\d+)',\s*'([^']+)',\s*'([\d.]+)'.*?\)", content)
    for kodepos, kelurahan, kodewilayah in rows:
        kodewilayah_clean = kodewilayah.replace('.', '')
        kelurahan_clean = re.sub(r'[\(\)]', '', kelurahan.lower().strip())
        mapping[kodewilayah_clean] = kodepos
        mapping[kelurahan_clean] = kodepos

    return mapping

def normalize_name(name):
    return re.sub(r'[\(\)]', '', name.lower().strip())

def add_postcode_to_villages(csv_path, mapping, output_path):
    updated_rows = []

    with open(csv_path, newline='', encoding='utf-8') as csvfile:
        reader = csv.reader(csvfile)
        for row in reader:
            kodewilayah = row[0]
            kelurahan = normalize_name(row[2])
            kodepos = mapping.get(kodewilayah) or mapping.get(kelurahan) or ''
            row.append(kodepos)
            updated_rows.append(row)

    with open(output_path, 'w', newline='', encoding='utf-8') as csvfile:
        writer = csv.writer(csvfile)
        writer.writerows(updated_rows)

    print(f'Selesai! File dengan kodepos ditulis ke: {output_path}')

if __name__ == '__main__':
    mapping = parse_sql_file(SQL_PATH)
    add_postcode_to_villages(CSV_PATH, mapping, OUTPUT_CSV_PATH)
