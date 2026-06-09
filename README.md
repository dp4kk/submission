A full-stack flight booking feature with PDF itinerary and confirmation email.

## Overview

Users can enter flight booking details through a form, save to database, and receive a confirmation email with a PDF itinerary attached. Supports English and German languages.

## Tech Stack

- **Frontend:** Next.js (App Router) + TypeScript, shadcn/ui, Tailwind CSS, react-hook-form + zod
- **Backend:** Laravel 11 (PHP 8.3+), MySQL, `barryvdh/laravel-dompdf` for PDF

## Setup

### Backend

```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

Backend runs at: `http://localhost:8000`

### Frontend

```bash
cd frontend
npm install
cp .env.example .env.local
npm run dev
```

Frontend runs at: `http://localhost:3000`

## Features

- Booking form with passenger details and flight segments
- Multi-language support (English/German)
- PDF itinerary generation with `laravel-dompdf`
- Confirmation email with PDF attached
- Form validation with zod

## API Endpoint

`POST /api/bookings` - Create booking with flight segments

## Assumptions

- One or more flight segments required
- SMTP email configured via environment variables
- All fields validated on frontend and backend

---

Built with Next.js + Laravel
