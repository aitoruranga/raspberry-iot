import time
import board
import adafruit_dht
import mysql.connector
from datetime import datetime

# CONFIGURATION
# Connect DHT11 Data pin to GPIO 4 (Pin 7)
DHT_PIN = board.D4

# DB Connection
DB_CONFIG = {
    "host": "localhost",
    "user": "aivet",
    "password": "aivet",
    "database": "raspberry_iot"
}

# Initialize sensor
sensor = adafruit_dht.DHT11(DHT_PIN)

def get_settings(cursor):
    cursor.execute("SELECT config_key, config_value FROM settings")
    settings = {row[0]: row[1] for row in cursor.fetchall()}
    return settings

def save_data(temp, hum, settings):
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()
    
    # Determine status
    status = 'normal'
    if temp <= float(settings['temp_cold']):
        status = 'cold'
    elif temp >= float(settings['temp_hot']):
        status = 'hot'
        
    sql = "INSERT INTO measurements (temperature, humidity, status) VALUES (%s, %s, %s)"
    cursor.execute(sql, (temp, hum, status))
    
    # Reset manual trigger
    cursor.execute("UPDATE settings SET config_value = '0' WHERE config_key = 'force_read'")
    
    conn.commit()
    cursor.close()
    conn.close()

def main():
    print("🚀 IoT Collector Started...")
    last_check = 0
    
    while True:
        try:
            conn = mysql.connector.connect(**DB_CONFIG)
            cursor = conn.cursor()
            settings = get_settings(cursor)
            cursor.close()
            conn.close()
            
            freq_seconds = int(settings['frequency_min']) * 60
            force_read = settings['force_read'] == '1'
            
            current_time = time.time()
            
            # Check if it's time to read or if a manual read was requested
            if (current_time - last_check >= freq_seconds) or force_read:
                print(f"[{datetime.now().strftime('%H:%M:%S')}] Reading sensor... (Manual: {force_read})")
                
                try:
                    temp = sensor.temperature
                    hum = sensor.humidity
                    
                    if temp is not None and hum is not None:
                        save_data(temp, hum, settings)
                        last_check = current_time
                        print(f"✔ Data saved: {temp}°C, {hum}%")
                    
                except RuntimeError as error:
                    # DHT sensors are sensitive, we ignore common read errors
                    print(f"⚠ Sensor error: {error.args[0]}")
                    time.sleep(2.0)
                    continue
            
            time.sleep(2) # Poll the database settings every 2 seconds
            
        except Exception as e:
            print(f"❌ Global Error: {e}")
            time.sleep(5)

if __name__ == "__main__":
    main()
