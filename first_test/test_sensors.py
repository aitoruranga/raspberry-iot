import time
import board
import adafruit_dht

# --- CONFIGURACIÓN ---
DHT_PIN = board.D4       # GPIO 4 (Pin Físico 7)

# Inicialización
try:
    dht_sensor = adafruit_dht.DHT11(DHT_PIN)
    print("--- 🔬 Test del Sensor DHT11 en Raspberry Pi 5 ---")
    print("Leyendo datos... (Pulsa Ctrl+C para parar)\n")
except Exception as e:
    print(f"❌ Error instalando sensor: {e}")
    dht_sensor = None

try:
    while True:
        if dht_sensor:
            try:
                # Lectura de datos
                temp = dht_sensor.temperature
                hum = dht_sensor.humidity
                
                if temp is not None and hum is not None:
                    print(f"[{time.strftime('%H:%M:%S')}] Temp: {temp}C | Humid: {hum}%")
                
            except RuntimeError:
                # Los sensores DHT son lentos, a veces fallan una lectura y hay que reintentar
                print(f"[{time.strftime('%H:%M:%S')}] DHT11: Esperando lectura estable...")
            except Exception as e:
                print(f"❌ Error: {e}")

        time.sleep(2.5) # Pausa de 2.5 segundos (el DHT11 necesita tiempo entre lecturas)

except KeyboardInterrupt:
    print("\nTest parado por el usuario.")
finally:
    if dht_sensor:
        dht_sensor.exit()
