from PIL import Image  # Python Imaging Library
import numpy as np

'''
# ---------- wczytywanie obrazu zapisanego w różnych formatach .bmp, .jpg, .png oraz pobieranie informacji o obrazie  -------------------
obrazek = Image.open("obrazek.bmp")  # wczytywanie obrazu
print("---------- informacje o obrazie")
print("tryb:", obrazek.mode)
print("format:", obrazek.format)
print("rozmiar:", obrazek.size)

obrazek

obrazek.show()

# ---------- wczytywanie obrazu do tablicy oraz pobieranie informacji o tablicach ------------------------------
dane_obrazka = np.asarray(obrazek)
print("---------------- informacje o tablicy obrazu----------------")
print("typ danych tablicy:", dane_obrazka.dtype)  # typ danych przechowywanych w tablicy
print("rozmiar tablicy:", dane_obrazka.shape)  # rozmiar tablicy - warto porównac z rozmiarami obrazka
print("liczba elementow:", dane_obrazka.size)  # liczba elementów tablicy
print("wymiar tablicy:", dane_obrazka.ndim)  # wymiar mówi czy to jest talica 1D, 2d, 3D ...
print("rozmiar wyrazu tablicy:",
      dane_obrazka.itemsize)  # pokazuje ile bajtów trzeba do zapisu wartości elementu
print("pierwszy wyraz:", dane_obrazka[0][0])
print("drugi wyraz:", dane_obrazka[1][0])
print("***************************************")
print(dane_obrazka)  # mozna  zobaczyć tablicę

ob_d = Image.fromarray(dane_obrazka)  # tworzenie obrazu z tablicy dane_obrazka (typ bool)
#ob_d
ob_d.show()

# ----- wyswietlanie informacji o obrazie -----------------------------

print("tryb:", ob_d.mode)
print("format:", ob_d.format)
print("rozmiar:", ob_d.size)

# ------------------------   wczytywanie obrazu do tablicy z jednoczesnym okresleniem typu danych ---------------------
# dane_obrazka1 = dane_obrazka * 1  # zmienia typ bool na int - działa w Python 3.8
# print(dane_obrazka1)
# dane_obrazka1 = dane_obrazka.astype(np.int_) # niektore wersje nie obsługuja trybu I
dane_obrazka1 = dane_obrazka.astype(np.uint8)
print(dane_obrazka1)

ob_d1 = Image.fromarray(dane_obrazka1)  # tworzenie obrazu z tablicy dane_obrazka1 (typ int)
# ----- wyswietlanie informacji o obrazie -----------------------------
print("tryb:", ob_d1.mode)
print("format:", ob_d1.format)
print("rozmiar:", ob_d1.size)

ob_d1.show()
# WAŻNE PYTANIE NA NASTEPNE ZAJECIA!!!  DLACZEGO ob_d1 widać jako obraz czarny?
#%%
# ---------------- zapisywanie obrazu do pliku -----------------
ob_d.save("obraz_zapisany.bmp")  # jako argument podajemy nazwę pliku wraz z rozszerzeniem,
# bo w zależności od tego w jakim formacie zapiszemy otrzymamy różne tablice obrazu - zobacz zadanie 7

#%%
# wczytywanie tablicy z pliku UWAGA! plik txt powinien zawierac same zera i jedynki oddzielane spacjami bez dodatkowych znaków jak w pliku dane.txt
t1 = np.loadtxt("dane.txt", dtype=np.bool_)
t2 = np.loadtxt("dane.txt", dtype=np.int_)
t3 = np.loadtxt("dane.txt", dtype=np.uint8)

# w zależnosci od tego, jakie operacje chcemy zrobić na tablicy, wybieramy jedną z powyższych postaci tablicy
print("typ danych tablicy t1:", t1.dtype)  # typ danych przechowywanych w tablicy
print("rozmiar tablicy t1 :", t1.shape)  # rozmiar tablicy - warto porównac z rozmiarami obrazka
print("wymiar tablicy t1 :", t1.ndim)  # wymiar mówi czy to jest talica 1D, 2d, 3D ...

print("typ danych tablicy t2:", t2.dtype)  # typ danych przechowywanych w tablicy
print("rozmiar tablicy t2 :", t2.shape)  # rozmiar tablicy - warto porównac z rozmiarami obrazka
print("wymiar tablicy t2 :", t2.ndim)  # wymiar mówi czy to jest talica 1D, 2d, 3D ...

print("typ danych tablicy t3:", t3.dtype)  # typ danych przechowywanych w tablicy
print("rozmiar tablicy t3 :", t3.shape)  # rozmiar tablicy - warto porównac z rozmiarami obrazka
print("wymiar tablicy t3 :", t3.ndim)  # wymiar mówi czy to jest talica 1D, 2d, 3D ...

print(t1)

print(t2)

print(t3)

# zapis tablicy do pliku
t1_text = open('t1.txt', 'w')
for rows in t1:
    for item in rows:
        t1_text.write(str(item) + ' ')
    t1_text.write('\n')

t1_text.close()
'''

# Zadnia samodzielne zaczynają się tu
# 2
inicjaly = Image.open("inicjaly.bmp")  # wczytywanie obrazu
print("---------- informacje o obrazie")
print("tryb:", inicjaly.mode)
print("format:", inicjaly.format)
print("rozmiar:", inicjaly.size)

#inicjaly.show()

# 3
dane_inicjalow = np.asarray(inicjaly)
print(dane_inicjalow)

dane_inicjalow1 = dane_inicjalow.astype(np.uint8)

inicjaly1_text = open('inicjaly1.txt', 'w')
for rows in dane_inicjalow1:
    for item in rows:
        inicjaly1_text.write(str(item) + ' ')
    inicjaly1_text.write('\n')

inicjaly1_text.close()

#in_d1 = Image.fromarray(dane_inicjalow1)

#4
print(dane_inicjalow[30][25])
print(dane_inicjalow[7][35])
print(dane_inicjalow[30][50])
print(dane_inicjalow[40][90])
print(dane_inicjalow[0][99])

#5
i1 = np.loadtxt("inicjaly1.txt", dtype=np.bool_)
print(i1)
print(dane_inicjalow)

#6
i2 = np.loadtxt("inicjaly1.txt", dtype=np.uint8)
print(i2)
print(dane_inicjalow)

ob_i2 = Image.fromarray(i2)
ob_i2.show()

#pokazuje czarny obraz, ponieważ uint8 zmienia format obrazu z zero-jedynkowego na RGB więc 0 odczytywane jest jako czerń

#7
print("Mapa bitowa 16-kolorowa")
inic1 = Image.open("inicjaly1.bmp")
print("tryb:", inic1.mode)
print("format:", inic1.format)
print("rozmiar:", inic1.size)
d1 = np.asarray(inic1)
print("typ danych tablicy:", d1.dtype)
print("rozmiar tablicy:", d1.shape)
print("wymiar tablicy:", d1.ndim)

print("-------------------------------")

print("Mapa bitowa 256-kolorowa")
inic2 = Image.open("inicjaly2.bmp")
print("tryb:", inic2.mode)
print("format:", inic2.format)
print("rozmiar:", inic2.size)
d2 = np.asarray(inic2)
print("typ danych tablicy:", d2.dtype)
print("rozmiar tablicy:", d2.shape)
print("wymiar tablicy:", d2.ndim)

print("-------------------------------")

print("Mapa bitowa 24-bitowa")
inic3 = Image.open("inicjaly3.bmp")
print("tryb:", inic3.mode)
print("format:", inic3.format)
print("rozmiar:", inic3.size)
d3 = np.asarray(inic3)
print("typ danych tablicy:", d3.dtype)
print("rozmiar tablicy:", d3.shape)
print("wymiar tablicy:", d3.ndim)

print("-------------------------------")

print("JPEG")
inic4 = Image.open("inicjaly4.jpg")
print("tryb:", inic4.mode)
print("format:", inic4.format)
print("rozmiar:", inic4.size)
d4 = np.asarray(inic4)
print("typ danych tablicy:", d4.dtype)
print("rozmiar tablicy:", d4.shape)
print("wymiar tablicy:", d4.ndim)

print("-----------------------------")

print("PNG")
inic5 = Image.open("inicjaly5.png")
print("tryb:", inic5.mode)
print("format:", inic5.format)
print("rozmiar:", inic5.size)
d5 = np.asarray(inic5)
print("typ danych tablicy:", d5.dtype)
print("rozmiar tablicy:", d5.shape)
print("wymiar tablicy:", d5.ndim)