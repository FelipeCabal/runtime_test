# Laboratorio de Comparación de Métodos para Importación Masiva CSV a PostgreSQL

## Introducción

Este laboratorio documenta la comparación del rendimiento de tres métodos para importar datos masivamente desde un archivo CSV hacia una base de datos PostgreSQL. El archivo contiene 500,000 registros, cada uno con 16 campos delimitados por punto y coma (;). El objetivo es evaluar la eficiencia temporal y la correcta inserción de datos de los siguientes métodos:

- **Método 1:** Inserción fila por fila utilizando `fgetcsv()`.
- **Método 2:** Uso del comando nativo `COPY FROM STDIN`.
- **Método 3:** Inserción por lotes utilizando `str_getcsv()` y `array_chunk()`.

Cada método fue implementado en PHP usando extensiones estándar, y el entorno de ejecución fue controlado para asegurar equidad en los resultados.

---

## Descripción de los Métodos

### Método 1: `fgetcsv()` línea a línea

Este método lee el archivo línea por línea usando `fgetcsv()`. Por cada fila válida, se arma una sentencia `INSERT INTO` individual que se ejecuta inmediatamente. Aunque ofrece mayor control y facilidad para manejo de errores por fila, tiene un rendimiento limitado debido al alto número de consultas.

**Ventajas:**

- Control total por fila.
- Facilidad para manejo de errores individuales.

**Desventajas:**

- Extremadamente lento en grandes volúmenes.
- Genera carga pesada sobre el motor PostgreSQL.

---

### Método 2: `COPY FROM STDIN`

Aprovecha el comando `COPY`, optimizado para transferencias masivas. Inicia una operación `COPY` y transmite cada línea usando `pg_put_line()`. Es el método más eficiente y cercano a herramientas nativas.

**Ventajas:**

- Máximo rendimiento.
- Uso directo del motor PostgreSQL para alta eficiencia.

**Desventajas:**

- Menor granularidad de control por fila.
- Requiere coincidencia exacta en formato con la tabla.

---

### Método 3: `str_getcsv()` por lotes

Lee todo el archivo en memoria, divide en lotes de 100 registros y ejecuta una sentencia `INSERT` por lote. Balancea rendimiento y control.

**Ventajas:**

- Más rápido que inserción línea por línea.
- Permite validaciones básicas y control de errores por lote.

**Desventajas:**

- Puede incrementar uso de memoria.
- Fallas en un lote descartan múltiples registros.

---

## Resultados de la Ejecución


1.) utilizando una laptop con intel core i5 de 13ava generación, con 1 tera de almacenamiento y 24GB de RAM
| Método          | Tiempo (segundos) | Filas Insertadas | Estado |
|-----------------|-------------------|------------------|--------|
| 1. Fila por fila | 152.456           | 500,000          | ✔️ OK  |
| 2. COPY         | 4.169             | 500,000          | ✔️ OK  |
| 3. Por lotes     | 16.408            | 500,000          | ✔️ OK  |

---

## Uso

Cada método está implementado en funciones PHP que pueden ser invocadas con una conexión PostgreSQL válida.

**Nota:** El archivo CSV esperado debe contener registros sin encabezado y estar delimitado por punto y coma (;).
