# Descripción del proyecto
Este proyecto busca solucionar el problema de registrar los horarios de entrada y de salida de los empleados de una empresa pequeña.

## Primera etapa (Versión 1.0)

Esta versión busca cubrir la funcionalidad mínima e indispensable, registrar las entradas y salidas en una base de datos.

### Requerimientos:

- Debe poder registrar el horario de entrada y salida en la base de datos pulsando un botón en la página y mostrar un mensaje “Registro correcto” o “Ocurrió un error, intente nuevamente”.
- Debe corroborar que el empleado efectivamente se encuentra en la empresa para poder hacerlo, caso contrario mostrar un mensaje “No se encuentra en la empresa”.

## Segunda etapa (Versión 1.5)

En esta versión se busca incluir la funcionalidad para ver los registros filtrados por meses y calcular las horas extras y/o debidas.

### Requerimientos:

- Poder acceder a un apartado para ver los registros de entrada/salida seleccionando el mes de interés.
- Poder acceder a un apartado para ver las horas extras y/o debidas.
- Agregar o modificar manualmente registros.

## Tercera etapa (Versión 2.0)

En esta versión incluirá usuarios y roles que tendrán distintos accesos a las funcionalidades.

### Requerimientos:

- Poder iniciar y cerrar sesión con usuarios independientes.
- Usuarios con distintos roles:
    - Usuario común:
        - Puede marcar entrada y salida.
        - Puede ver su registro de horarios por meses así como sus horas extras y/o debidas.
        - Puede solicitar la modificación de algún registro.
    - Usuario administrador:
        - Puede ver los horarios de entrada y salida de cualquier empleado y las horas extras o debidas.
        - Puede modificar los registros (incluir un panel con las peticiones de cambio).
        - Puede crear nuevos usuarios comunes.

## Tecnologías usadas

### Código

### Frontend:

- HTML
- CSS
    - Bootstrap
- Javascript

### Backend:

- PHP

### Despliegue

- Github
- 000webhost.com
