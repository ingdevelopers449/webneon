# Flujo de Autenticación en Laravel

Laravel incluye un robusto sistema de autenticación "out of the box" (listo para usar). Cuando utilizamos kits de inicio como Laravel Breeze (el cual instaló Neon Streaming para tener sus vistas y rutas base), la estructura completa de autenticación ya viene preconfigurada, pero es fundamental entender cómo encaja cada pieza.

A continuación, se detalla paso a paso cómo se comunican las Rutas, los Controladores, el Modelo y la Base de Datos.

---

## 1. Parámetros por defecto para Autenticar

Para autenticar a un usuario, Laravel utiliza de manera predeterminada el **`email`** y la **`password`**. 

1. **Email:** Actúa como el nombre de usuario (el identificador único principal).
2. **Password:** Laravel nunca guarda las contraseñas en texto plano. Las hashea utilizando el algoritmo **Bcrypt** (o Argon2). Cuando un usuario intenta iniciar sesión, Laravel toma la contraseña plana que ingresó, la hashea temporalmente y la compara de forma segura contra el hash almacenado en la base de datos.

> **Nota:** Aunque por defecto se usa el `email`, Laravel permite personalizar esto (por ejemplo, para usar un `username` o un `telefono`), modificando el controlador y los validadores que gestionan la petición de login.

---

## 2. Las Rutas: ¿Cómo sabe el sistema a dónde ir?

Todo comienza en los archivos de rutas. En Laravel 11 y versiones recientes, las rutas web están en `routes/web.php`, pero Breeze extrae todas las rutas de autenticación a un archivo separado llamado **`routes/auth.php`** para mayor orden.

```php
// Ejemplo de rutas en routes/auth.php

// Ruta GET: Muestra el formulario (la vista login.blade.php que hemos estilizado)
Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

// Ruta POST: Recibe los datos del formulario (email y password)
Route::post('login', [AuthenticatedSessionController::class, 'store']);
```

**Conexión:** 
Cuando el usuario visita `misitio.com/login`, Laravel dispara la ruta `GET 'login'`, que llama al método `create` del controlador. Cuando el usuario le da clic al botón "Log in", el formulario hace un `POST` a la ruta `login`, disparando el método `store` del mismo controlador.

---

## 3. El Controlador: El Cerebro del Login

El controlador encargado del login suele llamarse **`AuthenticatedSessionController`** (ubicado en `app/Http/Controllers/Auth/AuthenticatedSessionController.php`). 

Veamos lo que hace su método `store` (el que recibe el POST):

```php
public function store(LoginRequest $request): RedirectResponse
{
    // 1. Valida y autentica
    $request->authenticate();

    // 2. Protege contra robo de sesión
    $request->session()->regenerate();

    // 3. Redirige al dashboard
    return redirect()->intended(route('dashboard', absolute: false));
}
```

### ¿Qué pasa dentro de `$request->authenticate()`?
En lugar de escribir la lógica en el controlador directamente, Laravel Breeze crea un archivo llamado **`LoginRequest`** (un FormRequest). Dentro de ese archivo, Laravel hace lo siguiente:

1. **Validación:** Verifica que el `email` sea válido y que se haya enviado una `password`.
2. **Rate Limiting:** Se asegura de que el usuario no esté intentando hacer fuerza bruta (si falla 5 veces, lo bloquea por 1 minuto).
3. **El intento real (`Auth::attempt`):**
   ```php
   if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
       // Si falla, lanza un error de validación
       throw ValidationException::withMessages([
           'email' => trans('auth.failed'),
       ]);
   }
   ```
   La magia sucede en **`Auth::attempt()`**. Este método de Laravel toma el array `['email' => '...', 'password' => '...']`, va a la base de datos a través del Modelo, busca al usuario por el `email`, y si lo encuentra, compara el hash del `password`. Si todo coincide, "loguea" al usuario y crea la sesión.

### ¿Por qué `session()->regenerate()`?
Es una medida de seguridad (previene el *Session Fixation*). Al iniciar sesión, el ID interno de la sesión cambia. Así, si un hacker había obtenido tu ID de sesión antes de que te loguearas, ese ID queda inútil inmediatamente después de tu login.

---

## 4. El Modelo `User`: La conexión con la Base de Datos

Para que `Auth::attempt()` pueda ir a la base de datos, necesita de un Modelo de Eloquent. Por defecto usa el modelo **`User`** (`app/Models/User.php`).

### ¿Por qué el Modelo User es diferente a los demás?
Si abres `User.php`, notarás que no extiende de `Model` estándar, sino de **`Authenticatable`**:

```php
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
   // ...
}
```
`Authenticatable` es una clase especial que ya tiene programados todos los métodos que Laravel necesita para saber cosas como *"¿Cuál es la columna de la contraseña?"*, *"¿Cómo guardo el token de "Recuérdame" (remember_token)?"*, etc.

### Partes clave del Modelo User en Neon Streaming:

1. **Conexión a la Tabla Correcta:**
   Como no quisimos usar la tabla predeterminada `users`, le indicamos al modelo que apunte a la nuestra:
   ```php
   protected $table = 'usuarios_sistema';
   ```

2. **Atributos Asignables (`$fillable`):**
   Laravel, por seguridad, te obliga a declarar qué columnas pueden ser insertadas masivamente (Mass Assignment). Todo lo que esté aquí, puede ser guardado directamente desde un formulario.
   ```php
   #[Fillable(['name', 'email', 'password', 'id_rol', 'estado_cuenta', ...])]
   ```

3. **Atributos Ocultos (`$hidden`):**
   Cuando consultas a un usuario (ej. para enviarlo como JSON a una API), NUNCA quieres que la contraseña viaje en esa respuesta. El `$hidden` se encarga de censurarlo.
   ```php
   #[Hidden(['password', 'remember_token'])]
   ```

4. **Conversión de Datos (`casts`):**
   Laravel es inteligente y puede transformar los datos al sacarlos o meterlos a la BD. 
   ```php
   protected function casts(): array
   {
       return [
           'password' => 'hashed', // Automáticamente hace el Hash (Bcrypt) al guardar
       ];
   }
   ```
   Al indicar `'password' => 'hashed'`, cuando en nuestro Seeder escribimos `'password' => 'Admin123'`, el modelo lo intercepta y dice *"¡Espera! Esto debe ser hasheado"*, y lo guarda de forma segura.

---

## Resumen del Viaje (Ciclo de Vida)

1. El usuario entra a `misitio.com/login` **(Rutas: GET login)**.
2. Llena sus datos y presiona enviar **(Rutas: POST login)**.
3. La petición llega al **Controlador** `AuthenticatedSessionController@store`.
4. El Controlador delega la seguridad al **LoginRequest**.
5. **LoginRequest** revisa que los datos no estén vacíos y llama a **`Auth::attempt()`**.
6. **`Auth::attempt()`** busca en el archivo `config/auth.php` cuál modelo usar (descubre que es **`App\Models\User`**).
7. El **Modelo User** se conecta a la tabla `usuarios_sistema`, busca el email, comprueba que el hash coincida.
8. Si coincide, **Auth** aprueba, el **Controlador** regenera la sesión de seguridad y te envía a la página principal del dashboard.
