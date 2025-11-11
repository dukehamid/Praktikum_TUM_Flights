# Praktikum_TUM_Flights
# ✈️ Live Flight Dashboard — TUM CPEE Project

This project visualizes **real-time flight data above Garching (Munich)** using the **OpenSky Network API**.  
It automatically fetches the **4 nearest aircraft within a 5 km radius**, displays them on a live dashboard,  
and allows users to view detailed flight telemetry (altitude, speed, heading, and route) on a second dashboard by scanning a QR code.

---

## 🧭 Overview

### 🔹 Dashboard (`dashboard.html`)
Displays the **4 active flights** detected above Garching in real time for OpenSky API.

Each flight tile shows:
- 🔳 A **QR code** linked to the specific flight  
- ✈️ Each QR encodes the flight identifier (e.g., `icao24`)  
- 📡 When scanned, the QR sends the flight ID to **CPEE**, then redirects to the detailed view (`flight.html`)  

There are **no extra metrics or details** displayed on this page — only the 4 QR codes representing the nearest flights.  
The dashboard automatically reloads every few seconds to reflect updated flight data from the backend (`dashboard.json`).

---

### 🔹 Flight Details Page (`flight.html`)
This second dashboard (see screenshot below) shows **detailed telemetry** and a **live map** of the selected flight.

**Displayed Information:**
- **Route:** e.g. “München → Ziel (simuliert)”  
- **Altitude:** 10,600 m  
- **Speed:** 650 km/h  
- **Heading:** 270°  
- **Position:** latitude & longitude  
- **Map:** OpenStreetMap / Leaflet.js visualization of the flight trail  
- **Source:** OpenSky (BBox/Fallback)  
- **QR code:** leads back to the main dashboard  

If live data is temporarily unavailable, the page automatically switches to **simulation mode (SIM)**, showing a realistic movement path.

---

## ⚙️ System Architecture

| Component | Function | Language |
|------------|-----------|-----------|
| `server.py` | Periodically queries OpenSky API for aircraft near Garching (5 km radius) and updates `dashboard.json` | Python (Bottle) |
| `dashboard.html` | Displays exactly 4 flights as QR codes (no text data) | HTML, JavaScript |
| `flight.html` | Shows live/simulated telemetry, map, and path | HTML, JS, Leaflet.js |
| `scan.php` | Receives QR scan, sends event to CPEE, redirects to flight page | PHP |
| `scannedflight.json` | Stores last scanned flight | JSON |
| `callback.url` | Contains the CPEE callback endpoint | Text |
| `log.txt` | Server log output from the backend | Text |

---

## 🛰️ Data Source

- **API:** [OpenSky Network REST API](https://opensky-network.org/apidoc/rest.html)
- **Location:** Centered around *Garching bei München*  
- **Bounding Box:** ≈ 5 km radius (adjustable)
- **Update Interval:** 5–10 seconds  
- **Fallback:** Simulated mode if no API response is available  

---

## 🔧 How to Run

### 1. Connect to the TUM Lehre Server
```bash
ssh -i ~/.ssh/lehre_key go36jas@lehre.bpm.in.tum.de
