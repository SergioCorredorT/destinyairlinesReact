export const setDateInterval = (funcion, intervalo) =>
{
    let tiempoObjetivo = Date.now() + intervalo;
    let timeoutId;

    function tick()
    {
        const tiempoActual = Date.now();
        const desfase = tiempoActual - tiempoObjetivo;
        funcion();

        tiempoObjetivo = tiempoActual + intervalo - desfase;
        timeoutId = setTimeout(tick, intervalo - desfase);
    }

    timeoutId = setTimeout(tick, intervalo);

    // Función para detener el setDateInterval
    function clearDateInterval()
    {
        clearTimeout(timeoutId);
    }

    return clearDateInterval;

    /*
        // Ejemplo de uso:
        const miIntervalo = setDateInterval(() => {
        console.log("Esta función se ejecutará con precisión cada segundo.");
        }, 1000);

        // Para detener el setDateInterval en el momento
        miIntervalo();
    */
}
