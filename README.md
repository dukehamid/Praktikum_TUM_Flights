# Praktikum_TUM_Flights
# ✈️ Live Flight Dashboard — TUM CPEE Project

This project visualizes **real-time flight data above Garching (Munich)** using the **OpenSky Network API**.  
It automatically fetches the **4 nearest aircraft within a 5 km radius**, displays them on a live dashboard,  
and allows users to view detailed flight telemetry (altitude, speed, heading, and route) on a second dashboard by scanning a QR code.

---

## 🧭 Overview

### 🔹 Dashboard (`dashboard.html`)
Displays the **4 active flights** detected above Garching in real time.

Each flight tile shows:
- ✈️ Flight callsign  
- 📍 Position (latitude / longitude)  
- 🕓 Last update time  
- 📈 Altitude (m) and speed (km/h)  
- 🧭 Heading (°)  
- 🔳 A **QR code** linking to the detailed view (`flight.html`)

The dashboard automatically reloads every few seconds to reflect new flight data from the backend (`dashboard.json`).

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
| `dashboard.html` | Displays the 4 nearest flights and QR links | HTML, JavaScript |
| `flight.html` | Shows flight-specific data and map | HTML, JS, Leaflet.js |
| `scan.php` | Optional QR scan handler for CPEE workflow integration | PHP |
| `scannedflight.json` | Stores last scanned flight | JSON |
| `callback.url` | Contains CPEE callback endpoint | Text |
| `log.txt` | Server log output | Text |

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
