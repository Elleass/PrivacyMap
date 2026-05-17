# PrivacyMap — pełny kontekst projektu

## 1. Cel dokumentu

Ten dokument zbiera kompletny kontekst projektu **PrivacyMap**: założenia aplikacji, zakres funkcjonalny, wymagania zaliczeniowe, architekturę techniczną, model danych, logikę biznesową, strukturę własnego frameworka PHP, opis interfejsu użytkownika, propozycję dokumentacji, strategię implementacji oraz elementy potrzebne do obrony projektu.

Dokument może być używany jako:

- baza do README,
- opis koncepcji projektu,
- specyfikacja funkcjonalna,
- kontekst dla implementacji,
- kontekst dla prototypu Figmy,
- podstawa do diagramu ER,
- materiał pomocniczy podczas prezentacji lub obrony projektu.

---

## 2. Nazwa projektu

**PrivacyMap**

Alternatywne określenia projektu:

- Data Footprint Manager,
- Personal Data Exposure Tracker,
- Digital Privacy Map,
- Data Sovereignty Dashboard.

Główna nazwa rekomendowana: **PrivacyMap**.

---

## 3. Główna idea aplikacji

PrivacyMap to aplikacja webowa służąca do ręcznego zarządzania cyfrowym śladem danych użytkownika. Użytkownik może dodawać usługi internetowe, z których korzysta, oraz określać, jakie typy danych osobowych są lub mogą być z nimi powiązane.

Aplikacja pozwala odpowiedzieć na pytania:

- W jakich usługach znajdują się moje dane?
- Które firmy mają mój adres e-mail?
- Które firmy mają mój numer telefonu?
- Które firmy mogą przechowywać mój adres zamieszkania?
- Które usługi są najbardziej ryzykowne z punktu widzenia prywatności?
- Jakie działania mogę podjąć, aby ograniczyć ekspozycję danych?

Projekt nie polega na automatycznym logowaniu się do usług zewnętrznych ani na przechowywaniu cudzych haseł. Użytkownik ręcznie dodaje dane o usługach i typach danych.

---

## 4. Najważniejsze ograniczenie bezpieczeństwa

Aplikacja **nie przechowuje haseł, tokenów API ani danych logowania do zewnętrznych serwisów** takich jak Google, Meta, Netflix, Amazon, banki, sklepy internetowe czy portale społecznościowe.

PrivacyMap nie wykonuje automatycznego skanowania kont użytkownika. Jest to świadomy wybór projektowy, który:

- ogranicza ryzyko bezpieczeństwa,
- upraszcza implementację,
- zwiększa wiarygodność projektu,
- pozwala skupić się na relacyjnej bazie danych, CRUD, rolach użytkowników i dashboardzie,
- pozwala uniknąć niepotrzebnego przetwarzania wrażliwych danych.

Rekomendowany opis do dokumentacji:

> Aplikacja nie przechowuje danych logowania do zewnętrznych serwisów. Użytkownik ręcznie deklaruje, z jakich usług korzysta oraz jakie typy danych mogą być przez nie przetwarzane.

---

## 5. Problem, który rozwiązuje aplikacja

Współczesny użytkownik korzysta z wielu usług cyfrowych: poczty, mediów społecznościowych, sklepów internetowych, bankowości, aplikacji streamingowych, aplikacji zdrowotnych, usług logistycznych i platform edukacyjnych. Z czasem traci kontrolę nad tym:

- gdzie zakładał konta,
- jakie dane podał,
- które usługi mają dane kontaktowe,
- które usługi mogą mieć dane finansowe,
- które usługi mają dane lokalizacyjne,
- które konta warto usunąć,
- które usługi wymagają ograniczenia uprawnień.

PrivacyMap pomaga uporządkować te informacje w jednym miejscu i nadać im strukturę.

---

## 6. Grupa docelowa

Aplikacja jest przeznaczona dla:

- osób dbających o prywatność cyfrową,
- użytkowników chcących uporządkować swoje konta online,
- osób przygotowujących się do usunięcia nieużywanych kont,
- osób chcących ograniczyć ekspozycję danych osobowych,
- użytkowników chcących mieć prosty rejestr usług przetwarzających ich dane.

W kontekście projektu zaliczeniowego grupa docelowa może zostać opisana jako:

> Użytkownicy indywidualni, którzy chcą ręcznie monitorować, w jakich usługach internetowych znajdują się ich dane osobowe oraz jakie ryzyko prywatności wiąże się z tymi usługami.

---

## 7. Zakres projektu

### 7.1. Zakres właściwy

Projekt obejmuje:

- rejestrację użytkowników,
- logowanie użytkowników,
- role użytkowników,
- ręczne dodawanie usług,
- edycję usług,
- usuwanie usług,
- przypisywanie typów danych do usług,
- przypisywanie kategorii do usług,
- obliczanie poziomu ryzyka,
- dashboard statystyczny,
- widok listy usług,
- widok szczegółów usługi,
- rekomendacje prywatności,
- panel administratora,
- podstawową historię zmian,
- responsywny interfejs użytkownika,
- dokumentację projektu,
- diagram ER,
- prototyp Figma.

### 7.2. Zakres niewłaściwy

Projekt nie obejmuje:

- przechowywania haseł do zewnętrznych usług,
- logowania się do kont Google, Facebook, Amazon itp.,
- automatycznego pobierania danych z cudzych kont,
- realnego wycofywania zgód w zewnętrznych serwisach,
- automatycznego usuwania kont,
- integracji z bankami,
- przetwarzania rzeczywistych danych wrażliwych poza tym, co użytkownik sam wpisze w aplikacji.

---

## 8. Kryteria zaliczeniowe i sposób ich spełnienia

### 8.1. Repozytorium Git

Wymaganie: regularne commity i poprawna struktura plików.

Sposób spełnienia:

- utworzyć repozytorium Git od początku projektu,
- commitować regularnie po każdej logicznej funkcji,
- nie robić jednego dużego commita na końcu,
- zadbać o przejrzystą strukturę katalogów,
- dodać README,
- dodać pliki SQL do utworzenia bazy,
- dodać screeny aplikacji.

Przykładowe commity:

```text
init project structure
add custom router
add database connection using PDO
add user model and authentication controller
add registration and login views
add roles and access control
add services CRUD
add data types relation
add risk calculation
add dashboard statistics
add admin panel for categories
add responsive layout
add README and screenshots
```

---

### 8.2. Projekt Figma

Wymaganie: prototyp wykonany w narzędziu Figma.

Sposób spełnienia:

Przygotować w Figmie ekrany:

1. Logowanie.
2. Rejestracja.
3. Dashboard.
4. Lista usług.
5. Szczegóły usługi.
6. Dodawanie usługi.
7. Edycja usługi.
8. Insights / statystyki.
9. Panel administratora.
10. Wariant mobilny przynajmniej dla dashboardu i listy usług.

W README należy umieścić link do prototypu Figma.

---

### 8.3. Diagram ER

Wymaganie: diagram encji bazy danych.

Sposób spełnienia:

Przygotować diagram ER zawierający co najmniej:

- `users`,
- `roles`,
- `services`,
- `user_services`,
- `categories`,
- `data_types`,
- `service_data_types`,
- `recommendations`,
- `audit_logs`.

Diagram powinien pokazywać relacje:

- 1:N,
- N:M,
- relację z tabelą pośrednią,
- powiązanie użytkowników z rolami.

---

### 8.4. README ze screenami

Wymaganie: dokumentacja w pliku README ze screenami aplikacji.

Sposób spełnienia:

README powinno zawierać:

- opis projektu,
- cel aplikacji,
- funkcjonalności,
- technologie,
- instrukcję uruchomienia,
- strukturę katalogów,
- opis bazy danych,
- diagram ER,
- link do Figmy,
- screeny aplikacji,
- dane testowe,
- opis ról,
- informację o bezpieczeństwie.

---

### 8.5. Własny framework PHP

Wymaganie: kod napisany obiektowo, własny framework w PHP.

Sposób spełnienia:

Stworzyć prosty framework MVC zawierający:

- router,
- kontrolery,
- modele,
- widoki,
- klasę bazową kontrolera,
- klasę bazową modelu,
- klasę połączenia z bazą danych,
- obsługę sesji,
- walidację,
- prosty mechanizm autoryzacji ról.

Nie używać Laravela ani Symfony jako głównego frameworka.

---

### 8.6. PostgreSQL i złożona baza danych

Wymaganie: złożona baza danych PostgreSQL z trzema typami relacji.

Sposób spełnienia:

Użyć PostgreSQL oraz zaprojektować relacje:

- 1:N — użytkownik ma wiele usług,
- N:M — usługa użytkownika ma wiele typów danych, a typ danych występuje w wielu usługach,
- 1:N — usługa użytkownika ma wiele rekomendacji,
- N:1 — użytkownik należy do jednej roli,
- 1:N — użytkownik ma wiele wpisów historii zmian.

---

### 8.7. Responsywność

Wymaganie: aplikacja powinna być responsywna.

Sposób spełnienia:

- layout desktopowy z kartami i siatką,
- layout mobilny z jedną kolumną,
- responsywne formularze,
- karty usług dostosowane do małych ekranów,
- menu mobilne lub uproszczona nawigacja.

---

### 8.8. System logowania i role

Wymaganie: logowanie i rozróżnianie ról użytkowników.

Sposób spełnienia:

Role:

- `user`,
- `admin`.

Użytkownik może zarządzać własnymi usługami. Administrator może zarządzać globalnymi słownikami: usługami, kategoriami i typami danych.

---

## 9. Role użytkowników

### 9.1. Rola: użytkownik

Użytkownik może:

- zarejestrować konto,
- zalogować się,
- wylogować się,
- dodać usługę do swojego profilu,
- edytować własną usługę,
- usunąć własną usługę,
- przypisać typy danych do usługi,
- zobaczyć statystyki ekspozycji danych,
- zobaczyć poziom ryzyka usługi,
- oznaczyć rekomendację jako wykonaną,
- filtrować i sortować listę usług.

### 9.2. Rola: administrator

Administrator może:

- zarządzać katalogiem usług,
- zarządzać kategoriami,
- zarządzać typami danych,
- podejrzeć listę użytkowników,
- podejrzeć ogólne statystyki systemu,
- usuwać niepoprawne wpisy słownikowe,
- edytować opisy usług,
- edytować wagi ryzyka dla typów danych.

---

## 10. Główne funkcjonalności aplikacji

### 10.1. Rejestracja

Użytkownik podaje:

- imię lub nazwę użytkownika,
- adres e-mail,
- hasło,
- powtórzenie hasła.

Hasło jest zapisywane wyłącznie jako hash, np. przez `password_hash()` w PHP.

### 10.2. Logowanie

Użytkownik loguje się za pomocą:

- e-maila,
- hasła.

Hasło jest sprawdzane przez `password_verify()`.

### 10.3. Dashboard

Dashboard pokazuje najważniejsze informacje:

- liczba dodanych usług,
- liczba usług zawierających e-mail,
- liczba usług zawierających numer telefonu,
- liczba usług zawierających adres,
- liczba usług wysokiego ryzyka,
- ostatnio dodane usługi,
- najbardziej ryzykowne usługi,
- ogólny wskaźnik prywatności.

### 10.4. Lista usług

Lista usług pokazuje:

- nazwę usługi,
- opis,
- kategorię,
- typy danych,
- liczbę punktów danych,
- poziom ryzyka,
- przycisk szczegółów,
- opcje edycji i usuwania.

Funkcje listy:

- wyszukiwanie,
- filtrowanie po typie danych,
- filtrowanie po kategorii,
- filtrowanie po poziomie ryzyka,
- sortowanie po ryzyku,
- paginacja.

### 10.5. Dodawanie usługi

Formularz dodawania usługi zawiera:

- nazwę usługi,
- adres URL,
- kategorię,
- checkboxy typów danych,
- notatki,
- przycisk zapisu.

Po zapisaniu aplikacja automatycznie oblicza poziom ryzyka.

### 10.6. Szczegóły usługi

Widok szczegółów pokazuje:

- nazwę usługi,
- adres URL,
- opis,
- kategorię,
- poziom ryzyka,
- wynik punktowy,
- listę przypisanych typów danych,
- rekomendacje prywatności,
- przyciski edycji/usunięcia,
- historię zmian lub status usługi.

### 10.7. Insights / statystyki

Ekran statystyk pokazuje:

- łączną liczbę punktów danych,
- rozkład danych według typu,
- liczbę usług wysokiego, średniego i niskiego ryzyka,
- top usługi według liczby danych,
- rekomendację wykonania audytu,
- podsumowanie kondycji prywatności.

### 10.8. Rekomendacje prywatności

Aplikacja może generować rekomendacje na podstawie typu danych i poziomu ryzyka.

Przykłady:

- „Włącz uwierzytelnianie dwuskładnikowe”.
- „Sprawdź ustawienia prywatności konta”.
- „Usuń nieużywane konto”.
- „Ogranicz dostęp do lokalizacji”.
- „Zweryfikuj zapisane metody płatności”.
- „Sprawdź, czy możesz usunąć historię aktywności”.

---

## 11. Model danych

### 11.1. Tabela `roles`

Przechowuje role użytkowników.

```sql
CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE
);
```

Przykładowe dane:

```sql
INSERT INTO roles (name) VALUES ('user'), ('admin');
```

---

### 11.2. Tabela `users`

Przechowuje konta użytkowników aplikacji.

```sql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    role_id INTEGER NOT NULL REFERENCES roles(id),
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Relacja:

- wiele użytkowników może mieć jedną rolę,
- `users.role_id` → `roles.id`.

---

### 11.3. Tabela `categories`

Przechowuje kategorie usług.

```sql
CREATE TABLE categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
);
```

Przykładowe kategorie:

- Social Media,
- Finance,
- Shopping,
- Streaming,
- Health,
- Productivity,
- Education,
- Travel,
- Communication.

---

### 11.4. Tabela `services`

Globalny katalog usług. Administrator może zarządzać tą tabelą.

```sql
CREATE TABLE services (
    id SERIAL PRIMARY KEY,
    category_id INTEGER REFERENCES categories(id),
    name VARCHAR(150) NOT NULL,
    website_url VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Przykłady:

- Google,
- Meta,
- Amazon,
- Netflix,
- Spotify,
- X / Twitter,
- LinkedIn,
- PayPal.

---

### 11.5. Tabela `user_services`

Przechowuje usługi przypisane do konkretnego użytkownika.

```sql
CREATE TABLE user_services (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    service_id INTEGER REFERENCES services(id) ON DELETE SET NULL,
    custom_name VARCHAR(150),
    website_url VARCHAR(255),
    notes TEXT,
    risk_score INTEGER DEFAULT 0,
    risk_level VARCHAR(20) DEFAULT 'low',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Uwagi:

- `service_id` może wskazywać na globalny katalog usług,
- `custom_name` pozwala dodać usługę spoza katalogu,
- `risk_score` jest liczbowym wynikiem ryzyka,
- `risk_level` może przyjmować wartości: `low`, `medium`, `high`.

---

### 11.6. Tabela `data_types`

Przechowuje typy danych osobowych.

```sql
CREATE TABLE data_types (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    sensitivity_level INTEGER NOT NULL DEFAULT 1
);
```

Przykładowe typy danych:

- Email Address,
- Phone Number,
- Home Address,
- Payment Details,
- Financial Records,
- Behavioral Analytics,
- Location History,
- Contact Lists,
- Biometric Data,
- Identity Documents,
- Purchase History,
- Health Data.

---

### 11.7. Tabela `service_data_types`

Tabela pośrednia dla relacji wiele-do-wielu między usługami użytkownika a typami danych.

```sql
CREATE TABLE service_data_types (
    id SERIAL PRIMARY KEY,
    user_service_id INTEGER NOT NULL REFERENCES user_services(id) ON DELETE CASCADE,
    data_type_id INTEGER NOT NULL REFERENCES data_types(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_service_id, data_type_id)
);
```

Relacja:

- jedna usługa użytkownika może mieć wiele typów danych,
- jeden typ danych może występować w wielu usługach użytkowników.

---

### 11.8. Tabela `recommendations`

Przechowuje rekomendacje prywatności dla konkretnych usług użytkownika.

```sql
CREATE TABLE recommendations (
    id SERIAL PRIMARY KEY,
    user_service_id INTEGER NOT NULL REFERENCES user_services(id) ON DELETE CASCADE,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    priority VARCHAR(20) DEFAULT 'medium',
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Przykładowe rekomendacje:

- Enable Ad-Blocker,
- Clear Activity History,
- Review App Permissions,
- Remove Unused Account,
- Enable 2FA.

---

### 11.9. Tabela `audit_logs`

Przechowuje historię akcji użytkowników.

```sql
CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    action VARCHAR(100) NOT NULL,
    entity_type VARCHAR(100),
    entity_id INTEGER,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

Przykładowe akcje:

- `created_service`,
- `updated_service`,
- `deleted_service`,
- `completed_recommendation`,
- `login`,
- `logout`.

---

## 12. Typy relacji w bazie danych

### 12.1. Relacja 1:N — rola do użytkowników

```text
roles 1 ---- N users
```

Jedna rola może być przypisana wielu użytkownikom.

---

### 12.2. Relacja 1:N — użytkownik do usług użytkownika

```text
users 1 ---- N user_services
```

Jeden użytkownik może dodać wiele usług.

---

### 12.3. Relacja N:M — usługi użytkownika do typów danych

```text
user_services N ---- M data_types
```

Relacja realizowana przez tabelę pośrednią:

```text
service_data_types
```

---

### 12.4. Relacja 1:N — usługa użytkownika do rekomendacji

```text
user_services 1 ---- N recommendations
```

Jedna usługa może mieć wiele rekomendacji prywatności.

---

### 12.5. Relacja 1:N — użytkownik do historii zmian

```text
users 1 ---- N audit_logs
```

Jeden użytkownik może mieć wiele wpisów w historii działań.

---

## 13. Algorytm oceny ryzyka

Aplikacja powinna automatycznie obliczać poziom ryzyka na podstawie przypisanych typów danych.

### 13.1. Wagi typów danych

Przykładowe wagi:

| Typ danych | Waga |
|---|---:|
| Email Address | 1 |
| Phone Number | 2 |
| Home Address | 3 |
| Payment Details | 4 |
| Financial Records | 5 |
| Behavioral Analytics | 3 |
| Location History | 4 |
| Contact Lists | 3 |
| Biometric Data | 5 |
| Identity Documents | 5 |
| Purchase History | 2 |
| Health Data | 5 |

Waga może być zapisana w kolumnie `data_types.sensitivity_level`.

### 13.2. Progi ryzyka

```text
0–3 pkt: low
4–8 pkt: medium
9+ pkt: high
```

### 13.3. Przykład

Usługa: Meta

Typy danych:

- Email Address: 1,
- Phone Number: 2,
- Home Address: 3,
- Behavioral Analytics: 3.

Suma:

```text
1 + 2 + 3 + 3 = 9
```

Poziom ryzyka:

```text
high
```

### 13.4. Pseudokod

```php
function calculateRisk(array $dataTypes): array
{
    $score = 0;

    foreach ($dataTypes as $dataType) {
        $score += $dataType['sensitivity_level'];
    }

    if ($score >= 9) {
        $level = 'high';
    } elseif ($score >= 4) {
        $level = 'medium';
    } else {
        $level = 'low';
    }

    return [
        'score' => $score,
        'level' => $level,
    ];
}
```

---

## 14. Proponowane dane startowe

### 14.1. Role

```sql
INSERT INTO roles (name) VALUES
('user'),
('admin');
```

### 14.2. Kategorie

```sql
INSERT INTO categories (name, description) VALUES
('Social Media', 'Social networks and communication platforms'),
('Finance', 'Banking, payments and financial services'),
('Shopping', 'Marketplaces and online stores'),
('Streaming', 'Video and music streaming platforms'),
('Health', 'Health, sport and wellness applications'),
('Productivity', 'Work and productivity tools'),
('Education', 'Learning platforms and education services'),
('Travel', 'Travel, hotels and transport services'),
('Communication', 'Email, messaging and communication tools');
```

### 14.3. Typy danych

```sql
INSERT INTO data_types (name, description, sensitivity_level) VALUES
('Email Address', 'User email address used for account identification and communication', 1),
('Phone Number', 'User phone number used for contact or verification', 2),
('Home Address', 'Physical residential or delivery address', 3),
('Payment Details', 'Payment cards or billing methods', 4),
('Financial Records', 'Financial history, transactions or account-related data', 5),
('Behavioral Analytics', 'Tracking, preferences, clicks and usage behavior', 3),
('Location History', 'Geolocation history or location-based records', 4),
('Contact Lists', 'Imported or synchronized user contacts', 3),
('Biometric Data', 'Fingerprint, face, body or biometric identifiers', 5),
('Identity Documents', 'Documents used for identity verification', 5),
('Purchase History', 'History of user purchases and orders', 2),
('Health Data', 'Health, fitness or medical information', 5);
```

### 14.4. Usługi globalne

```sql
INSERT INTO services (category_id, name, website_url, description) VALUES
(1, 'Google', 'https://google.com', 'Search, email, identity and cloud services'),
(1, 'Meta', 'https://meta.com', 'Social platforms including Facebook, Instagram and WhatsApp'),
(3, 'Amazon', 'https://amazon.com', 'Marketplace and e-commerce platform'),
(4, 'Netflix', 'https://netflix.com', 'Video streaming platform'),
(4, 'Spotify', 'https://spotify.com', 'Music streaming platform'),
(1, 'X / Twitter', 'https://x.com', 'Social media and microblogging platform'),
(2, 'PayPal', 'https://paypal.com', 'Online payment platform'),
(5, 'ActiveTrack Health', NULL, 'Health and biometric activity tracking service');
```

---

## 15. Architektura aplikacji

Rekomendowana architektura: prosty, własny framework MVC.

### 15.1. MVC

Aplikacja powinna być podzielona na:

- **Model** — logika dostępu do danych,
- **View** — szablony HTML,
- **Controller** — obsługa żądań i koordynacja działania.

### 15.2. Przykładowa struktura katalogów

```text
/privacy-map
  /app
    /Controllers
      AuthController.php
      DashboardController.php
      ServiceController.php
      RecommendationController.php
      InsightController.php
      AdminController.php
    /Models
      User.php
      Role.php
      Service.php
      UserService.php
      Category.php
      DataType.php
      Recommendation.php
      AuditLog.php
    /Core
      Router.php
      Controller.php
      Model.php
      Database.php
      Auth.php
      Validator.php
      View.php
      Middleware.php
    /Views
      /layouts
        main.php
        auth.php
      /auth
        login.php
        register.php
      /dashboard
        index.php
      /services
        index.php
        show.php
        create.php
        edit.php
      /insights
        index.php
      /admin
        index.php
        categories.php
        data-types.php
        services.php
        users.php
  /config
    database.php
    app.php
  /database
    schema.sql
    seed.sql
  /public
    index.php
    /assets
      /css
        style.css
      /js
        app.js
      /img
  /docs
    er-diagram.png
    figma-link.txt
    screenshots
  README.md
```

---

## 16. Główne klasy frameworka

### 16.1. `Router`

Odpowiada za mapowanie adresów URL na kontrolery.

Przykładowe trasy:

```php
$router->get('/', [DashboardController::class, 'index']);
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->post('/logout', [AuthController::class, 'logout']);

$router->get('/services', [ServiceController::class, 'index']);
$router->get('/services/create', [ServiceController::class, 'create']);
$router->post('/services', [ServiceController::class, 'store']);
$router->get('/services/{id}', [ServiceController::class, 'show']);
$router->get('/services/{id}/edit', [ServiceController::class, 'edit']);
$router->post('/services/{id}/update', [ServiceController::class, 'update']);
$router->post('/services/{id}/delete', [ServiceController::class, 'delete']);

$router->get('/insights', [InsightController::class, 'index']);

$router->get('/admin', [AdminController::class, 'index']);
$router->get('/admin/services', [AdminController::class, 'services']);
$router->get('/admin/categories', [AdminController::class, 'categories']);
$router->get('/admin/data-types', [AdminController::class, 'dataTypes']);
```

### 16.2. `Database`

Odpowiada za połączenie z PostgreSQL przez PDO.

Zasady:

- używać prepared statements,
- nie sklejać ręcznie zapytań z danymi użytkownika,
- przechowywać konfigurację bazy w `/config/database.php`,
- nie commitować prawdziwych haseł do bazy.

### 16.3. `Controller`

Bazowa klasa kontrolera może mieć metody:

- `view($template, $data = [])`,
- `redirect($path)`,
- `requireAuth()`,
- `requireAdmin()`.

### 16.4. `Model`

Bazowa klasa modelu może przechowywać połączenie z bazą i wspólne metody.

### 16.5. `Auth`

Obsługuje:

- aktualnego użytkownika,
- sprawdzanie zalogowania,
- sprawdzanie roli,
- zapis użytkownika w sesji,
- wylogowanie.

### 16.6. `Validator`

Obsługuje walidację formularzy:

- wymagane pola,
- poprawny adres e-mail,
- minimalna długość hasła,
- poprawny URL,
- poprawne ID,
- maksymalna długość tekstu.

---

## 17. Kontrolery

### 17.1. `AuthController`

Metody:

- `loginForm()` — pokazuje formularz logowania,
- `login()` — obsługuje logowanie,
- `registerForm()` — pokazuje formularz rejestracji,
- `register()` — tworzy konto,
- `logout()` — kończy sesję.

### 17.2. `DashboardController`

Metody:

- `index()` — pokazuje dashboard użytkownika.

Dane potrzebne do dashboardu:

- liczba usług użytkownika,
- liczba usług z e-mailem,
- liczba usług z telefonem,
- liczba usług z adresem,
- ostatnio dodane usługi,
- usługi wysokiego ryzyka,
- procent usług niskiego/średniego/wysokiego ryzyka.

### 17.3. `ServiceController`

Metody:

- `index()` — lista usług,
- `show($id)` — szczegóły usługi,
- `create()` — formularz dodawania,
- `store()` — zapis nowej usługi,
- `edit($id)` — formularz edycji,
- `update($id)` — aktualizacja,
- `delete($id)` — usunięcie.

### 17.4. `InsightController`

Metody:

- `index()` — statystyki i analiza danych.

### 17.5. `AdminController`

Metody:

- `index()` — dashboard admina,
- `services()` — zarządzanie katalogiem usług,
- `categories()` — zarządzanie kategoriami,
- `dataTypes()` — zarządzanie typami danych,
- `users()` — lista użytkowników.

---

## 18. Modele

### 18.1. `User`

Odpowiedzialność:

- tworzenie użytkownika,
- wyszukiwanie po e-mailu,
- pobieranie roli,
- aktualizacja profilu.

### 18.2. `Service`

Odpowiedzialność:

- globalny katalog usług,
- pobieranie usług dla formularzy,
- dodawanie usług przez administratora.

### 18.3. `UserService`

Odpowiedzialność:

- usługi konkretnego użytkownika,
- CRUD,
- filtrowanie,
- sortowanie,
- obliczanie statystyk.

### 18.4. `DataType`

Odpowiedzialność:

- lista typów danych,
- wagi ryzyka,
- CRUD dla administratora.

### 18.5. `Recommendation`

Odpowiedzialność:

- generowanie rekomendacji,
- lista rekomendacji dla usługi,
- oznaczanie rekomendacji jako wykonanej.

### 18.6. `AuditLog`

Odpowiedzialność:

- zapisywanie działań użytkownika,
- pobieranie historii działań.

---

## 19. Trasy aplikacji

### 19.1. Publiczne

```text
GET  /login
POST /login
GET  /register
POST /register
```

### 19.2. Dla zalogowanego użytkownika

```text
GET  /
GET  /dashboard
GET  /services
GET  /services/create
POST /services
GET  /services/{id}
GET  /services/{id}/edit
POST /services/{id}/update
POST /services/{id}/delete
GET  /insights
POST /logout
```

### 19.3. Dla administratora

```text
GET  /admin
GET  /admin/services
POST /admin/services
GET  /admin/categories
POST /admin/categories
GET  /admin/data-types
POST /admin/data-types
GET  /admin/users
```

---

## 20. Interfejs użytkownika

### 20.1. Ogólny styl UI

Aplikacja powinna mieć wygląd nowoczesnego dashboardu SaaS:

- jasne tło,
- białe karty,
- duża typografia,
- zaokrąglone narożniki,
- czytelne odstępy,
- ograniczona paleta kolorów,
- tagi dla typów danych,
- statusy ryzyka wyróżnione kolorem,
- przejrzysta nawigacja.

### 20.2. Kolory statusów ryzyka

Rekomendowane oznaczenia:

- niski poziom ryzyka: zielony,
- średni poziom ryzyka: niebieskoszary lub fioletowoszary,
- wysoki poziom ryzyka: czerwony.

### 20.3. Nawigacja

Główne pozycje:

- Dashboard,
- Services,
- Insights,
- Admin — widoczne tylko dla administratora,
- Profile,
- Logout.

---

## 21. Ekrany aplikacji

### 21.1. Dashboard

Zawiera:

- nazwę aplikacji,
- główną nawigację,
- nagłówek „Data Exposure Overview”,
- karty statystyk,
- listę najbardziej ryzykownych usług,
- listę ostatnio dodanych usług,
- kartę „Privacy Health”,
- sekcję „Personal Data Footprint”.

Przykładowe metryki:

- Total Services,
- Email Usage,
- Phone Usage,
- Address Exposure.

Uwaga: należy jasno rozróżnić `services` i `data points`.

---

### 21.2. Lista usług

Zawiera:

- nagłówek „Data Sources”,
- filtry: All Sources, Email, Phone, Address,
- wyszukiwarkę,
- sortowanie po poziomie ryzyka,
- przycisk „Add Service”,
- listę usług w formie kart,
- paginację.

Karta usługi zawiera:

- ikonę,
- nazwę,
- opis,
- tagi typów danych,
- liczbę punktów danych,
- poziom ryzyka.

---

### 21.3. Szczegóły usługi

Zawiera:

- breadcrumb: Services > Meta,
- nazwę usługi,
- URL,
- opis,
- kartę statusu bezpieczeństwa,
- listę danych współdzielonych,
- rekomendacje prywatności,
- przyciski edycji i usunięcia.

Uwaga projektowa:

Przycisk „Revoke All Permissions” może być mylący, jeśli aplikacja nie integruje się z usługami zewnętrznymi. Lepsze nazwy:

- „Mark as Removed”,
- „Create Removal Task”,
- „Generate Deletion Checklist”,
- „Oznacz jako usunięte”,
- „Dodaj zadanie usunięcia danych”.

---

### 21.4. Insights

Zawiera:

- nagłówek „Data Points Found”,
- karty z rozkładem danych,
- Risk Overview,
- Top Services by Data,
- przycisk lub CTA „Run Deep Audit”.

W wersji zaliczeniowej „Run Deep Audit” może oznaczać wygenerowanie lokalnego podsumowania na podstawie danych zapisanych w bazie, a nie realny audyt zewnętrznych kont.

---

### 21.5. Add Service

Zawiera formularz:

- Service Name,
- Service URL,
- Category,
- What data is stored?,
- Optional Notes,
- Cancel,
- Save.

Po prawej stronie można pokazać szacowanie ryzyka:

- Risk Estimation,
- Privacy Score,
- Encryption Check,
- Pro Tip.

W implementacji można dynamicznie aktualizować wynik ryzyka po zaznaczeniu checkboxów albo obliczać go dopiero po zapisaniu formularza.

---

## 22. Spójność pojęć w UI

Ważne jest rozróżnienie pojęć:

### 22.1. Service

Pojedyncza usługa lub konto, np. Google, Meta, Netflix.

### 22.2. Data Type

Rodzaj danych, np. email, telefon, adres, płatności.

### 22.3. Data Point

Jedno wystąpienie typu danych w konkretnej usłudze.

Przykład:

Jeżeli usługa Meta ma:

- Email Address,
- Phone Number,
- Home Address,

to można liczyć to jako 3 data points.

### 22.4. Risk Score

Suma wag typów danych przypisanych do usługi.

### 22.5. Risk Level

Kategoria ryzyka wynikająca z `risk_score`:

- low,
- medium,
- high.

---

## 23. Rekomendowana wersja danych demonstracyjnych

Dla ręcznie zarządzanej aplikacji lepiej nie używać zbyt dużych liczb typu 432 usługi, bo może to wyglądać nierealistycznie.

Rekomendowane liczby dla demo:

- 24 usługi,
- 58 data points,
- 18 usług z e-mailem,
- 9 usług z telefonem,
- 4 usługi z adresem,
- 3 usługi wysokiego ryzyka,
- 8 usług średniego ryzyka,
- 13 usług niskiego ryzyka.

Jeśli pozostaje liczba 432, należy jasno określić, że oznacza ona `data points`, a nie liczbę usług.

---

## 24. Walidacja formularzy

### 24.1. Rejestracja

Walidować:

- imię/nazwa użytkownika niepuste,
- e-mail poprawny,
- e-mail unikalny,
- hasło minimum 8 znaków,
- powtórzone hasło zgodne z pierwszym.

### 24.2. Dodawanie usługi

Walidować:

- nazwa usługi niepusta,
- URL poprawny lub pusty,
- wybrana kategoria istnieje,
- typy danych istnieją w bazie,
- notatka nie przekracza limitu znaków.

### 24.3. Edycja usługi

Walidować:

- użytkownik jest właścicielem usługi,
- usługa istnieje,
- dane formularza są poprawne.

---

## 25. Bezpieczeństwo aplikacji

### 25.1. Hasła

- nie zapisywać haseł jako plain text,
- używać `password_hash()`,
- sprawdzać hasła przez `password_verify()`.

### 25.2. SQL Injection

- używać PDO prepared statements,
- nie sklejać zapytań SQL z danymi użytkownika.

### 25.3. XSS

- escapować dane wyświetlane w widokach,
- używać `htmlspecialchars()`.

### 25.4. CSRF

Dobrze dodać token CSRF do formularzy POST.

### 25.5. Kontrola dostępu

- użytkownik może edytować tylko własne usługi,
- administrator ma dostęp do panelu admina,
- trasy chronione sprawdzają sesję.

### 25.6. Dane wrażliwe

- aplikacja nie powinna wymagać wpisywania prawdziwych haseł do usług,
- dane demonstracyjne powinny być fikcyjne,
- w README należy opisać ograniczenie zakresu.

---

## 26. Responsywność

### 26.1. Desktop

- szeroki dashboard,
- karty w kilku kolumnach,
- lista usług jako szerokie karty,
- sidebar lub górna nawigacja.

### 26.2. Tablet

- karty w dwóch kolumnach,
- formularze w jednej lub dwóch kolumnach,
- uproszczone odstępy.

### 26.3. Mobile

- jedna kolumna,
- karty jedna pod drugą,
- formularze pełnej szerokości,
- menu mobilne,
- krótsze nagłówki,
- przyciski pełnej szerokości.

---

## 27. Panel administratora

Panel administratora powinien być prosty, ale realnie działający.

### 27.1. Zarządzanie kategoriami

Administrator może:

- dodać kategorię,
- edytować kategorię,
- usunąć kategorię,
- zobaczyć liczbę usług w kategorii.

### 27.2. Zarządzanie typami danych

Administrator może:

- dodać typ danych,
- edytować opis,
- zmienić wagę ryzyka,
- usunąć typ danych, jeśli nie jest używany.

### 27.3. Zarządzanie usługami globalnymi

Administrator może:

- dodać usługę do katalogu,
- edytować nazwę, URL i opis,
- przypisać kategorię,
- usunąć usługę z katalogu.

### 27.4. Lista użytkowników

Administrator może:

- zobaczyć listę użytkowników,
- zobaczyć liczbę usług dodanych przez użytkownika,
- zobaczyć datę utworzenia konta.

Nie musi mieć możliwości podglądu szczegółowych prywatnych danych użytkownika, chyba że projekt zakłada taką funkcję. Bezpieczniej ograniczyć podgląd.

---

## 28. Statystyki i zapytania SQL

### 28.1. Liczba usług użytkownika

```sql
SELECT COUNT(*)
FROM user_services
WHERE user_id = :user_id;
```

### 28.2. Liczba usług z konkretnym typem danych

```sql
SELECT COUNT(DISTINCT us.id)
FROM user_services us
JOIN service_data_types sdt ON sdt.user_service_id = us.id
JOIN data_types dt ON dt.id = sdt.data_type_id
WHERE us.user_id = :user_id
  AND dt.name = 'Email Address';
```

### 28.3. Liczba punktów danych

```sql
SELECT COUNT(*)
FROM user_services us
JOIN service_data_types sdt ON sdt.user_service_id = us.id
WHERE us.user_id = :user_id;
```

### 28.4. Top usługi według ryzyka

```sql
SELECT *
FROM user_services
WHERE user_id = :user_id
ORDER BY risk_score DESC
LIMIT 5;
```

### 28.5. Liczba usług według poziomu ryzyka

```sql
SELECT risk_level, COUNT(*)
FROM user_services
WHERE user_id = :user_id
GROUP BY risk_level;
```

---

## 29. Generowanie rekomendacji

Rekomendacje można generować po zapisaniu usługi.

### 29.1. Reguły przykładowe

Jeśli usługa ma `Payment Details`:

- „Review saved payment methods”.
- „Remove unused cards from this account”.

Jeśli ma `Location History`:

- „Limit location permissions”.
- „Clear location history if the service allows it”.

Jeśli ma `Behavioral Analytics`:

- „Disable personalized ads”.
- „Clear off-platform activity”.

Jeśli ma `Contact Lists`:

- „Review contact synchronization settings”.

Jeśli ma `Biometric Data`:

- „Verify biometric data retention policy”.

Jeśli ryzyko jest wysokie:

- „Consider deleting the account if it is no longer used”.
- „Enable two-factor authentication”.

---

## 30. Przykładowy opis projektu do README

```text
PrivacyMap to aplikacja webowa wspierająca użytkowników w zarządzaniu ich cyfrowym śladem danych. System pozwala ręcznie rejestrować usługi internetowe, z których korzysta użytkownik, oraz określać, jakie typy danych osobowych są z nimi powiązane. Na podstawie przypisanych typów danych aplikacja wylicza poziom ryzyka prywatności, prezentuje statystyki ekspozycji danych oraz generuje podstawowe rekomendacje ograniczające ryzyko. Aplikacja nie przechowuje haseł ani danych logowania do zewnętrznych serwisów.
```

---

## 31. Proponowana struktura README

```text
# PrivacyMap

## Opis projektu

## Cel aplikacji

## Funkcjonalności

## Role użytkowników

## Technologie

## Architektura

## Struktura katalogów

## Model bazy danych

## Diagram ER

## Prototyp Figma

## Screeny aplikacji

## Instrukcja uruchomienia

## Dane testowe

## Bezpieczeństwo

## Autor
```

---

## 32. Instrukcja uruchomienia — przykład do README

```text
1. Sklonuj repozytorium:
   git clone <adres-repozytorium>

2. Przejdź do katalogu projektu:
   cd privacy-map

3. Skonfiguruj połączenie z bazą danych w pliku:
   config/database.php

4. Utwórz bazę PostgreSQL:
   createdb privacymap

5. Zaimportuj strukturę bazy:
   psql -d privacymap -f database/schema.sql

6. Zaimportuj dane testowe:
   psql -d privacymap -f database/seed.sql

7. Uruchom lokalny serwer PHP:
   php -S localhost:8000 -t public

8. Otwórz aplikację w przeglądarce:
   http://localhost:8000
```

---

## 33. Dane testowe do README

```text
Administrator:
email: admin@example.com
hasło: password123

Użytkownik:
email: user@example.com
hasło: password123
```

Uwaga: dane testowe są fikcyjne i przeznaczone wyłącznie do środowiska lokalnego.

---

## 34. Minimalny zakres MVP

Minimalna wersja aplikacji powinna zawierać:

1. Rejestrację.
2. Logowanie.
3. Role: user/admin.
4. Dashboard użytkownika.
5. CRUD usług użytkownika.
6. Typy danych jako checkboxy.
7. Relację N:M między usługami i typami danych.
8. Automatyczne obliczanie ryzyka.
9. Widok szczegółów usługi.
10. Insights/statystyki.
11. Panel administratora do zarządzania kategoriami i typami danych.
12. PostgreSQL.
13. README.
14. Diagram ER.
15. Link do Figmy.
16. Responsywny layout.

---

## 35. Funkcje dodatkowe, jeśli zostanie czas

- eksport raportu do PDF,
- eksport danych do CSV,
- wyszukiwarka usług,
- paginacja,
- tryb dark mode,
- wykresy statystyczne,
- historia zmian na koncie,
- status usługi: active, removed, pending removal,
- checklisty usuwania kont,
- filtrowanie po wielu typach danych naraz,
- dynamiczne obliczanie ryzyka w formularzu przez JavaScript.

---

## 36. Priorytety implementacji

### Etap 1 — fundament

- struktura projektu,
- router,
- połączenie z bazą,
- layout,
- podstawowe widoki.

### Etap 2 — użytkownicy

- rejestracja,
- logowanie,
- sesje,
- role.

### Etap 3 — baza domenowa

- kategorie,
- usługi,
- typy danych,
- usługi użytkownika,
- tabela pośrednia.

### Etap 4 — CRUD

- lista usług,
- dodawanie,
- edycja,
- usuwanie,
- szczegóły.

### Etap 5 — logika ryzyka

- wagi typów danych,
- przeliczanie wyniku,
- status low/medium/high.

### Etap 6 — dashboard i insights

- statystyki,
- ostatnio dodane,
- top ryzykowne usługi,
- rozkład typów danych.

### Etap 7 — administrator

- zarządzanie kategoriami,
- zarządzanie typami danych,
- zarządzanie usługami globalnymi.

### Etap 8 — dopracowanie

- responsywność,
- walidacja,
- komunikaty błędów,
- screeny,
- README,
- diagram ER.

---

## 37. Potencjalne ryzyka projektowe

### 37.1. Zbyt ambitny zakres

Nie należy próbować implementować automatycznego skanowania internetu ani integracji z zewnętrznymi kontami. To zwiększa ryzyko niedokończenia projektu.

### 37.2. Mylące przyciski

Przyciski typu „Revoke All Permissions” sugerują realne wykonanie operacji w zewnętrznym serwisie. Lepiej użyć nazw wskazujących na lokalną akcję w aplikacji.

### 37.3. Niespójne liczby w UI

Trzeba jasno rozróżnić:

- usługi,
- punkty danych,
- typy danych,
- wyniki ryzyka.

### 37.4. Brak panelu admina

Role użytkowników muszą być realnie widoczne w aplikacji. Administrator powinien mieć odrębny widok i odrębne uprawnienia.

### 37.5. Brak responsywności

Należy przygotować CSS dla urządzeń mobilnych, ponieważ responsywność jest jednym z wymagań.

---

## 38. Propozycja nazewnictwa w polskiej wersji aplikacji

| Angielski termin | Polski odpowiednik |
|---|---|
| Dashboard | Panel główny |
| Services | Usługi |
| Insights | Analiza |
| Data Sources | Źródła danych |
| Data Points | Punkty danych |
| Data Exposure | Ekspozycja danych |
| Risk Level | Poziom ryzyka |
| High Risk | Wysokie ryzyko |
| Medium Risk | Średnie ryzyko |
| Low Risk | Niskie ryzyko |
| Add Service | Dodaj usługę |
| Shared Data | Udostępnione dane |
| Recommendations | Rekomendacje |
| Privacy Health | Kondycja prywatności |

Można utrzymać aplikację po angielsku, ale dokumentację napisać po polsku. Ważne, aby nazwy były konsekwentne.

---

## 39. Przykładowe scenariusze użytkownika

### 39.1. Dodanie usługi

1. Użytkownik loguje się.
2. Przechodzi do zakładki Services.
3. Klika Add Service.
4. Wpisuje nazwę usługi.
5. Wybiera kategorię.
6. Zaznacza typy danych.
7. Dodaje notatkę.
8. Zapisuje formularz.
9. System oblicza ryzyko.
10. Użytkownik widzi usługę na liście.

### 39.2. Analiza ryzyka

1. Użytkownik przechodzi do Insights.
2. Widzi rozkład typów danych.
3. Widzi usługi wysokiego ryzyka.
4. Przechodzi do szczegółów jednej usługi.
5. Sprawdza rekomendacje.
6. Oznacza rekomendację jako wykonaną.

### 39.3. Praca administratora

1. Administrator loguje się.
2. Przechodzi do panelu Admin.
3. Dodaje nowy typ danych.
4. Ustawia wagę ryzyka.
5. Nowy typ danych pojawia się w formularzu dodawania usługi.

---

## 40. Przykładowe kryteria akceptacji

### 40.1. Logowanie

- użytkownik z poprawnymi danymi może się zalogować,
- użytkownik z błędnym hasłem widzi komunikat błędu,
- niezalogowany użytkownik nie ma dostępu do dashboardu.

### 40.2. Dodawanie usługi

- zalogowany użytkownik może dodać usługę,
- usługa zostaje zapisana w bazie,
- przypisane typy danych trafiają do tabeli pośredniej,
- ryzyko jest obliczane automatycznie.

### 40.3. Uprawnienia

- użytkownik nie może edytować usług innego użytkownika,
- zwykły użytkownik nie ma dostępu do panelu admina,
- administrator ma dostęp do panelu admina.

### 40.4. Responsywność

- dashboard działa na desktopie,
- lista usług działa na telefonie,
- formularz dodawania usługi mieści się na małym ekranie.

---

## 41. Przygotowanie do obrony projektu

Podczas prezentacji warto podkreślić:

1. Aplikacja rozwiązuje realny problem zarządzania cyfrowym śladem danych.
2. Nie przechowuje haseł do zewnętrznych usług.
3. Dane o usługach są dodawane ręcznie.
4. Projekt wykorzystuje PostgreSQL i wiele relacji.
5. Zastosowano własny framework MVC w PHP.
6. Aplikacja ma logowanie i role.
7. Użytkownik ma swój dashboard i własne dane.
8. Administrator ma osobny panel.
9. Ryzyko jest wyliczane na podstawie wag typów danych.
10. UI zostało zaprojektowane w Figmie i zaimplementowane responsywnie.

---

## 42. Krótka wersja opisu do prezentacji

```text
PrivacyMap to aplikacja webowa do ręcznego mapowania cyfrowego śladu danych użytkownika. Użytkownik dodaje usługi, z których korzysta, przypisuje im typy danych osobowych, a system wylicza poziom ryzyka prywatności i pokazuje rekomendacje. Aplikacja zawiera logowanie, role użytkowników, panel administratora, dashboard, statystyki, CRUD usług oraz relacyjną bazę danych PostgreSQL. Projekt nie przechowuje haseł do zewnętrznych usług i nie wykonuje automatycznego logowania na konta użytkownika.
```

---

## 43. Ostateczna rekomendacja projektowa

Najbezpieczniejsza i najbardziej opłacalna wersja projektu to:

- ręczny katalog usług,
- typy danych jako checkboxy,
- automatyczne obliczanie ryzyka,
- czytelny dashboard,
- panel admina,
- PostgreSQL z relacjami,
- własny framework PHP,
- dobra dokumentacja i screeny.

Nie należy komplikować projektu automatycznym wykrywaniem kont ani integracjami z zewnętrznymi serwisami. Dla zaliczenia ważniejsza będzie kompletność, poprawna architektura, działające CRUD-y, relacje w bazie, role i jakość dokumentacji.

---

## 44. Checklista końcowa

Przed oddaniem projektu należy sprawdzić:

- [ ] Repozytorium ma regularne commity.
- [ ] Struktura katalogów jest czytelna.
- [ ] Aplikacja uruchamia się lokalnie.
- [ ] PostgreSQL działa.
- [ ] Istnieje `schema.sql`.
- [ ] Istnieje `seed.sql`.
- [ ] Działa rejestracja.
- [ ] Działa logowanie.
- [ ] Hasła są haszowane.
- [ ] Działa wylogowanie.
- [ ] Istnieją role user/admin.
- [ ] Użytkownik widzi tylko swoje usługi.
- [ ] Administrator ma osobny panel.
- [ ] Działa dodawanie usługi.
- [ ] Działa edycja usługi.
- [ ] Działa usuwanie usługi.
- [ ] Działa przypisywanie typów danych.
- [ ] Ryzyko jest obliczane automatycznie.
- [ ] Dashboard pokazuje statystyki.
- [ ] Insights pokazuje rozkład danych.
- [ ] Widok szczegółów usługi działa.
- [ ] UI jest responsywne.
- [ ] README zawiera opis projektu.
- [ ] README zawiera instrukcję uruchomienia.
- [ ] README zawiera screeny.
- [ ] README zawiera link do Figmy.
- [ ] Jest diagram ER.
- [ ] Dane testowe są fikcyjne.
- [ ] Dokumentacja informuje, że aplikacja nie przechowuje haseł do zewnętrznych usług.

---

## 45. Najkrótszy opis zakresu technicznego

```text
Projekt zostanie wykonany jako aplikacja webowa w PHP z własnym prostym frameworkiem MVC. Dane będą przechowywane w PostgreSQL. System będzie zawierał rejestrację, logowanie, role użytkowników, panel administratora, CRUD usług, relację wiele-do-wielu między usługami a typami danych, dashboard statystyczny oraz automatyczne obliczanie poziomu ryzyka prywatności. Interfejs zostanie przygotowany na podstawie prototypu Figma i dostosowany do urządzeń mobilnych.
```

---

## 46. Najważniejsze zdanie definiujące projekt

> PrivacyMap to ręczny menedżer cyfrowego śladu danych, który pomaga użytkownikowi zrozumieć, jakie firmy i usługi mogą przetwarzać jego dane osobowe oraz jaki poziom ryzyka prywatności wynika z tych powiązań.
