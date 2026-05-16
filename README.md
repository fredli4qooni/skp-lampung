# Sistem Ketahanan Pangan (SKP) — Lampung Province

A web-based food security management system for tracking rice availability and generating multi-year supply forecasts using ARIMA (AutoRegressive Integrated Moving Average) time-series modeling. Built on a dual-engine architecture: **Laravel 13** handles the backend and UI, while **Python** runs the data science computation layer.

---

## Features

- **Authentication & Authorization** — Secure login with role-based access control via Laravel Breeze.
- **Data Management (CRUD)** — Historical recording of production, consumption, and availability figures.
- **ARIMA Forecasting** — 3-year supply projections computed via Python `statsmodels`.
- **Interactive Visualization** — Combined historical and predicted data rendered with Chart.js.
- **Automated Reports** — Export to PDF (DOMPDF) and Excel (Maatwebsite).

---

## Prerequisites

- PHP >= 8.2 and Composer
- Node.js and NPM
- Python >= 3.9
- MySQL or MariaDB

---

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/fredli4qooni/skp-lampung.git
cd skp-lampung
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Configure Environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and set your database connection values (e.g. `DB_DATABASE=skp_lampung`).

### 4. Run Database Migrations

```bash
php artisan migrate
```

> **Note:** After registering the first account, manually set the `role` column to `admin` in the database to enable access to the admin panel.

---

### 5. Set Up the Python Environment

Create and activate a virtual environment from the project root:

```bash
# Create
python -m venv venv

# Activate — Windows
venv\Scripts\activate

# Activate — macOS/Linux
source venv/bin/activate
```

Install Python dependencies:

```bash
pip install -r requirements.txt
```

---

## Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

Open your browser and navigate to `http://127.0.0.1:8000`.