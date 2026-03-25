# 🌡️ Raspberry Pi 5 IoT Environment Monitor

A high-performance, premium-designed web application to monitor temperature and humidity using a **Raspberry Pi 5** and a **DHT11** sensor. Built with PHP, Python, and MariaDB.

## ✨ Features
- **Real-time Monitoring**: Dashboard with automatic refreshes.
- **Dynamic UX**: The background color changes based on temperature (Cold/Normal/Hot).
- **Manual Trigger**: Request an immediate measurement via the "Measure Now" button.
- **Configurable Settings**: Adjust frequency (1min to 24h) and temperature thresholds.
- **Responsive Design**: Modern "glassmorphism" aesthetic built with Vanilla CSS.

## 🛠️ Hardware Requirements
- **Raspberry Pi 5** (4GB recommended).
- **DHT11** Temperature/Humidity sensor.
- Jumper wires.

## 📦 Software Stack
- **OS**: Raspberry Pi OS (64-bit / Bookworm).
- **Web Server**: Apache2 / Nginx + PHP 8.x.
- **Database**: MariaDB / MySQL.
- **Python**: 3.11+.

## 🚀 Setup Instructions

### 1. Hardware Verification (CRITICAL)
Before setting up the database and web server, verify your DHT11 sensor is correctly wired and the drivers are working on your Pi 5. 

See [first_test/INSTRUCTIONS.txt](first_test/INSTRUCTIONS.txt) for the quick setup guide and `test_sensors.py`.

### 2. System Preparation
Update your system and install necessary packages:
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install apache2 mariadb-server php php-mysql python3-pip python3-venv python3-full -y
```

### ### 3. Database Setup
Create the database and user:
```bash
sudo mysql -u root < schema.sql
```
*Tip: Edit `db.php` and `collector.py` with your database credentials.*

### 4. Dependencies
Create a virtual environment and install the Pi 5 compatible libraries:
```bash
python3 -m venv --system-site-packages venv
source venv/bin/activate
pip install -r requirements.txt
```

### 5. Running the Background Service
Run the collector script to start recording data:
```bash
source venv/bin/activate
python3 collector.py
```
*Note: For production, set this up as a `systemd` service.*

### 6. Web Access
Copy the PHP files to your web server directory:
```bash
sudo cp *.php style.css /var/www/html/
```
Then visit `http://your-raspberry-ip/index.php`.

## ⚙️ Settings
- **Cold Threshold**: Set the temperature for the "Cold" blue background.
- **Hot Threshold**: Set the temperature for the "Hot" pink/red background.
- **Frequency**: Choose how often the sensor takes a reading automatically.

## 📜 License
MIT License. Feel free to use and modify!
