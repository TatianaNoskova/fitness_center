# 🏋️‍♂️ WorldClass - Gestión de Gimnasio & Clases

¡Bienvenido a **WorldClass**! Un sistema integral de gestión para gimnasios y estudios deportivos desarrollado en **Laravel**. Este proyecto está orientado a automatizar los procesos de inscripción a clases, control de membresías, gestión de sedes etc.

---

## 🚀 Características Clave (Features)

El sistema cuenta con un control de acceso por **Roles (Mapeo de Usuarios)** y responde a tres perfiles principales:

### 👤 1. Panel de Socio (Cliente)
* **Gestión de Perfil:** Activación de cuenta seleccionando categoría (`NORMAL`, `ESTUDIANTE`, `VIP`) y asignación a una sede.
* **Inscripción Inteligente:** Sistema de reservas de clases en tiempo real con control estricto de capacidad.
* **Historial de Pagos:** Control de cuotas mensuales, combos contratados y servicios extras (`PENDIENTE` / `PAGADO`).

### 📋 2. Panel de Entrenador (Coach)
* **Control de Clases:** Visualización de la agenda diaria de clases asignadas.
* **Control de Asistencia:** Panel interactivo para marcar la asistencia de los socios (`asistencia`: *Sí / No*) una vez iniciada la clase.

### ⚙️ 3. Panel de Administrador
* **CRUD Completo:** Gestión total de Sedes (`Sedes`), Planes de membresía, Combos y Servicios. Gestión de socios & entrenadores.
* **Control de Red:** Monitoreo global de clases, asignación de profesores y horarios.

---

## 🛠️ Arquitectura y Patrones de Diseño Aplicados

Para este proyecto académico, se prestó especial atención a las buenas prácticas de programación Orientada a Objetos (OOP) y patrones de diseño:

* **Arquitectura del Proyecto:** MODELO / VISTA / CONTROLADOR.
* **Pattern Composite / Structural:** Implementado en la gestión de **Combos y Servicios**. Un Combo actúa como un objeto compuesto que calcula su precio de forma dinámica sumando o aplicando reglas sobre los Servicios individuales que contiene.
* **Patrón Observer (Eventos de Sistema):** Utilizado para desacoplar procesos secundarios. Por ejemplo, al cancelar/eliminar una `Clase`, el `ClaseObserver` reacciona automáticamente y gestiona el flujo de notificaciones/caché para los socios.
* **Patrón Strategy (Comportamiento):** Aplicado para el **cálculo dinámico de precios, descuentos y recargos** según la categoría del Socio (`NORMAL`, `ESTUDIANTE`, `VIP`).

* ### 🌤️ Integración con API Externa (Clima & Recomendaciones)
* **Consumo de API REST:** Conexión en tiempo real con un servicio meteorológico externo (OpenMeteo.com) para obtener las condiciones climáticas actuales.
* **Sistema de Recomendaciones Inteligentes:** Implementación de lógica de negocio que analiza el estado del clima (temperatura, lluvia, humedad) para sugerir dinámicamente el tipo de entrenamiento ideal.

---

## 💻 Tecnologías Utilizadas

* **Backend:** PHP 8.2.12 / Laravel 12.59.0
* **Base de Datos:** MySQL
* **Frontend:** Blade Templates, Tailwind CSS


