import pandas as pd
import os

EXCEL_FILE = 'INDONESIA2025.xlsx'

df = pd.read_excel(EXCEL_FILE, dtype=str)

print("Kolom yang tersedia:")
print(df.columns.tolist())
print("\nSample data:")
print(df.head())

df.columns = [c.strip().lower().replace(' ', '_').replace('/', '_').replace('(', '').replace(')', '').replace(',', '') for c in df.columns]

print("\nKolom setelah normalisasi:")
print(df.columns.tolist())

output_dir = "../resources/csv"
os.makedirs(output_dir, exist_ok=True)

print("\nAnalisis struktur kode:")
print("Sample kode_desa:", df['kode_desa'].head().tolist())
print("Sample kode_provinsi:", df['kode_provinsi'].head().tolist())
print("Sample kode_kabupaten:", df['kode_kabupaten'].head().tolist())
print("Sample kode_kecamatan:", df['kode_kecamatan'].head().tolist())

provinsi_df = df[['kode_provinsi', 'nama_provinsi']].drop_duplicates()
provinsi_df['code'] = provinsi_df['kode_provinsi'].str.replace('.', '', regex=False)
provinsi_df = provinsi_df[['code', 'nama_provinsi']]
provinsi_df.columns = ['code', 'name']
provinsi_df = provinsi_df.sort_values('code')
provinsi_df.to_csv(f"{output_dir}/provinces.csv", index=False, header=False)
print(f"✅ Provinsi: {len(provinsi_df)} records")

kabupaten_df = df[['kode_provinsi', 'kode_kabupaten', 'nama_kabupaten']].drop_duplicates()
kabupaten_df['code'] = kabupaten_df['kode_kabupaten'].str.replace('.', '', regex=False)
kabupaten_df['province_code'] = kabupaten_df['kode_provinsi'].str.replace('.', '', regex=False)
kabupaten_df = kabupaten_df[['code', 'province_code', 'nama_kabupaten']]
kabupaten_df.columns = ['code', 'province_code', 'name']
kabupaten_df = kabupaten_df.sort_values('code')
kabupaten_df.to_csv(f"{output_dir}/cities.csv", index=False, header=False)
print(f"✅ Kabupaten/Kota: {len(kabupaten_df)} records")

kecamatan_df = df[['kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'nama_kecamatan']].drop_duplicates()
kecamatan_df['code'] = kecamatan_df['kode_kecamatan'].str.replace('.', '', regex=False)
kecamatan_df['city_code'] = kecamatan_df['kode_kabupaten'].str.replace('.', '', regex=False)
kecamatan_df = kecamatan_df[['code', 'city_code', 'nama_kecamatan']]
kecamatan_df.columns = ['code', 'city_code', 'name']
kecamatan_df = kecamatan_df.sort_values('code')
kecamatan_df.to_csv(f"{output_dir}/districts.csv", index=False, header=False)
print(f"✅ Kecamatan: {len(kecamatan_df)} records")

kelurahan_df = df[['kode_desa', 'kode_provinsi', 'kode_kabupaten', 'kode_kecamatan', 'nama_kelurahan_desa_desa_adat']].copy()
kelurahan_df['code'] = kelurahan_df['kode_desa'].str.replace('.', '', regex=False)
kelurahan_df['district_code'] = kelurahan_df['kode_kecamatan'].str.replace('.', '', regex=False)
kelurahan_df = kelurahan_df[['code', 'district_code', 'nama_kelurahan_desa_desa_adat']]
kelurahan_df.columns = ['code', 'district_code', 'name']
kelurahan_df = kelurahan_df.sort_values('code')
kelurahan_df.to_csv(f"{output_dir}/villages.csv", index=False, header=False)
print(f"✅ Desa/Kelurahan: {len(kelurahan_df)} records")

print("\n🎉 Parsing selesai! File CSV tersimpan di:", output_dir)
print("\nFile yang dihasilkan:")
print("- provinces.csv")
print("- cities.csv")
print("- districts.csv")
print("- villages.csv")
